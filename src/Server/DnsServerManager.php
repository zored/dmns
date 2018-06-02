<?php

declare(strict_types=1);

namespace Zored\Dmns\Server;

use yswery\DNS\ResolverInterface;
use yswery\DNS\Server;
use Zored\Dmns\Model\Address;

class DnsServerManager implements ServerManagerInterface
{
    /** @var ResolverInterface */
    private $resolver;

    public function __construct(ResolverInterface $resolver)
    {
        $this->resolver = $resolver;
    }

    public function listen(Address $address): void
    {
        (new Server($this->resolver, $address->getHost(), $address->getPort()))->start();
    }
}
