<?php

declare(strict_types=1);

namespace Zored\Dmns\Model;

class Address
{
    /**
     * @var string
     */
    private $host;

    /**
     * @var int
     */
    private $port;

    public function __construct(string $host, int $port)
    {
        $this->host = $host;
        $this->port = $port;
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function getPort(): int
    {
        return $this->port;
    }

    public function __toString(): string
    {
        return "{$this->host}:{$this->port}";
    }
}
