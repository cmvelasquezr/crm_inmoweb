<?php
namespace Src\Repositories;

use Src\Models\Client;
use Src\Config\Database;
use PDO;
use Exception;

require_once __DIR__ . '/../Config/Database.php';
require_once __DIR__ . '/../Models/Property.php';
require_once __DIR__ . '/../Models/Client.php';
require_once __DIR__ . '/PropertyRepository.php';

class MySQLClientRepository extends BaseRepository implements ClientRepository {
     public function __construct() {
        try {
            $database = new Database();
            $this->conn = $database->getConnection();

            if (!$this->conn) {
                throw new Exception("No se pudo establecer la conexión con la base de datos.");
            }
        } catch (Exception $e) {
            $message = "Error al inicializar MySQLClientRepository: " . $e->getMessage();
            $this->setError($message);
            throw new Exception($message, 500, $e);
        }
    }

    public function getClients(): array {

        if (!$this->isHealthy()) {
            $message = "Repositorio no saludable: " . $this->getLastError();
            throw new Exception($message);
        }

        try {
            $stmt = $this->conn->query("SELECT * FROM clients");

            if (!$stmt) {
                $message = "La consulta SQL para obtener clientes ha fallado.";
                $this->setError($message);
            }

            $clients = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $clients[] = new Client(
                    $row['id'],
                    $row['name'],
                    json_decode($row['preferred_zones'], true),
                    $row['min_price'],
                    $row['max_price'],
                    $row['min_rooms'],
                    $row['max_rooms'],
                    (bool)$row['wants_garage'],
                    (bool)$row['wants_terrace']
                );
            }

            return $clients;

        }   catch (Exception $e) {
            $message = "Error al obtener clientes: " . $e->getMessage();
            $this->setError($message);
            throw $e;
        }
    }
}
