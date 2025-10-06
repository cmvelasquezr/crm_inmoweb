<?php
namespace Src\Repositories;

use Src\Models\Client;

interface ClientRepository {
    /** @return Client[] */
    public function getClients(): array;
}
