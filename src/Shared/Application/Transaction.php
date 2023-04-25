<?php

namespace App\Shared\Application;

interface Transaction
{
    public function handleNow(callable $callable): void;

    public function setEntityManager($em): void;

    // TODO think about moving to LazyTransaction
    public function begin(): void;

    public function commit(callable $callable): void;

    public function rollback(): void;

    //TODO end
}
