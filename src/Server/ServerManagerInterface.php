<?php

declare(strict_types=1);

namespace Zored\Dmns\Server;

use Zored\Dmns\Model\Address;

interface ServerManagerInterface
{
    public function listen(Address $address): void;
}
