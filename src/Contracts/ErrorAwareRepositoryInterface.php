<?php

namespace Src\Contracts;

interface ErrorAwareRepositoryInterface {
    /**
     * Verifica si el repositorio está operativo (por ejemplo, si la conexión es válida).
     */
    public function isHealthy(): bool;

    /**
     * Devuelve el último error registrado, si existe.
     */
    public function getLastError(): ?string;

    /**
     * Limpia el estado de error actual.
     */
    public function clearError(): void;
}
