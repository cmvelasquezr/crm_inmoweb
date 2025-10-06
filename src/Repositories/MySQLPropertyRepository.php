<?php
namespace Src\Repositories;

use Src\Models\Property;
use Src\Models\Client;
use Src\Config\Database;
use PDO;

require_once __DIR__ . '/../Config/Database.php';
require_once __DIR__ . '/../Models/Property.php';
require_once __DIR__ . '/../Models/Client.php';
require_once __DIR__ . '/PropertyRepository.php';

class MySQLPropertyRepository implements PropertyRepository {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getById(int $id): ?Property {
        $stmt = $this->conn->prepare("SELECT * FROM properties WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? new Property(
            $row['id'],
            $row['title'],
            $row['zone'],
            $row['price'],
            $row['rooms'],
            (int)$row['area'],
            (bool)$row['terrace'],
            (bool)$row['garage']
        ) : null;
    }

    public function getAll(): array {
        $stmt = $this->conn->query("SELECT * FROM properties");
        $properties = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $properties[] = new Property(
                $row['id'],
                $row['title'],
                $row['zone'],
                $row['price'],
                $row['rooms'],
                (int)$row['area'],
                (bool)$row['terrace'],
                (bool)$row['garage']
            );
        }
        return $properties;
    }

    public function getClients(): array {
        $stmt = $this->conn->query("SELECT * FROM clients");
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
                (bool)$row['wants_terrace'],
                (bool)$row['wants_garage']
            );
        }
        return $clients;
    }
}
