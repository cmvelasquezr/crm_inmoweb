<?php
namespace Src\Repositories;

use Src\Contracts\ErrorAwareRepositoryInterface;
use PDO;

abstract class BaseRepository implements ErrorAwareRepositoryInterface {
    protected ?PDO $conn = null;
    protected ?string $lastError = null;

    public function isHealthy(): bool {
        return $this->conn !== null && $this->lastError === null;
    }

    public function getLastError(): ?string {
        return $this->lastError;
    }

    public function clearError(): void {
        $this->lastError = null;
    }

    protected function setError(string $message): void {
        $this->lastError = $message;
    }
}
