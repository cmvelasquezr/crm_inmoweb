<?php
namespace Src\Repositories;

use Src\Models\Property;
use Src\Config\Database;
use PDO;
use Exception;

require_once __DIR__ . '/../Config/Database.php';
require_once __DIR__ . '/../Models/Property.php';
require_once __DIR__ . '/../Models/Client.php';
require_once __DIR__ . '/PropertyRepository.php';

class MySQLPropertyRepository extends BaseRepository implements PropertyRepository {
    public function __construct() {

        try {
            $database = new Database();
            $this->conn = $database->getConnection();

            if (!$this->conn) {
                throw new Exception("No se pudo establecer la conexión con la base de datos.");
            }
        } catch (Exception $e) {
            $message = "Error al inicializar MySQLPropertyRepository: " . $e->getMessage();
            $this->setError($message);
            throw new Exception($message, 500, $e);
        }
    }

    public function getById(int $id): ?Property {

        if (!$this->isHealthy()) {
            throw new Exception("Repositorio no saludable: " . $this->getLastError());
        }

        try {
            $stmt = $this->conn->prepare("SELECT * FROM properties WHERE id = ?");
            $stmt->execute([$id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$row) {
                return null;
            }

            return new Property(
                $row['id'],
                $row['title'],
                $row['zone'],
                $row['price'],
                $row['rooms'],
                (bool)$row['garage'],
                (bool)$row['terrace'],
                (int)$row['area']
            );
        } catch (Exception $e) {
            $message = "Error al obtener propiedad por ID: " . $e->getMessage();
            $this->setError($message);
            throw $e;
        }
    }

    public function getAll(): array {

        if (!$this->isHealthy()) {
            throw new Exception("Repositorio no saludable: " . $this->getLastError());
        }

        try {
            $stmt = $this->conn->query("SELECT * FROM properties");

            if (!$stmt) {
                $message = "La consulta SQL para obtener propiedades fallado.";
                $this->setError($message);
            }

            $properties = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $properties[] = new Property(
                    $row['id'],
                    $row['title'],
                    $row['zone'],
                    $row['price'],
                    $row['rooms'],
                    (bool)$row['garage'],
                    (bool)$row['terrace'],
                    (int)$row['area']
                );
            }

            return $properties;

        } catch (Exception $e) {
            $message = "Error al obtener propiedades: " . $e->getMessage();
            $this->setError($message);
            throw new Exception($message, 500, $e);
        }
    }

}
