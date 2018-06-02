<?php

declare(strict_types=1);

namespace Zored\Dmns\Host;

use Zored\Dmns\Model\Address;

interface DnsUpdaterInterface
{
    /**
     * TLD is handled on port.
     *
     * @param Address $address
     * @param string  $tld
     *
     * @return bool Success?
     */
    public function update(Address $address, string $tld): bool;
}
