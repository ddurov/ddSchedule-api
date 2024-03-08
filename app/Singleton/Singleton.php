<?php declare(strict_types=1);

namespace Api\Singleton;

interface Singleton
{
    /**
     * @return mixed
     */
    public static function getInstance(): mixed;
}