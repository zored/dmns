<?php

declare(strict_types=1);

namespace Zored\Dmns\Host;

use Zored\Dmns\Model\Address;

class EachDnsUpdater implements DnsUpdaterInterface
{
    /** @var DnsUpdaterInterface[] */
    private $updaters;

    public function __construct(DnsUpdaterInterface ...$updaters)
    {
        $this->updaters = $updaters;
    }

    public function update(Address $address, string $tld): bool
    {
        foreach ($this->updaters as $updater) {
            if (!$updater->update($address, $tld)) {
                return false;
            }
        }

        return true;
    }
}
