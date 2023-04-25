<?php

declare(strict_types=1);

namespace App\Identity\UI\Dashboard\Company\Cli\Command;

use Doctrine\DBAL\Connection;

final class EmptyDbNameConnection extends Connection
{
    public static function fromConnection(Connection $connection): self
    {
        $params = $connection->getParams();
        $params['dbname'] = null;

        return new self(
            $params,
            $connection->getDriver(),
            $connection->getConfiguration(),
            $connection->getEventManager()
        );
    }
}
