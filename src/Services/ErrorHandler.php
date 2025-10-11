<?php
namespace Src\Services;

class ErrorHandler {
    private ?string $userMessage = null;
    private ?string $technicalMessage = null;
    private ?string $rootCause = null;

    public function handle(\Throwable $e, string $userMessage = 'Ha ocurrido un error inesperado.'): void {
        $this->userMessage = $userMessage;
        $this->technicalMessage = $e->getMessage();
        $this->rootCause = $e->getPrevious() ? $e->getPrevious()->getMessage() : null;

        //Registrar el error  error_log
        error_log("[ERROR] {$this->technicalMessage}" . ($this->rootCause ? " | Causa: {$this->rootCause}" : ""));

        $this->renderHtml($this->rootCause);
    }

    private function renderHtml($message): void {
        $viewData = ['error_message' => $message];
        extract($viewData);
        include __DIR__ . '/../../views/error_view.php';
        exit;
    }

    public function getUserMessage(): ?string {
        return $this->userMessage;
    }

    public function getTechnicalMessage(): ?string {
        return $this->technicalMessage;
    }

    public function getRootCause(): ?string {
        return $this->rootCause;
    }
}