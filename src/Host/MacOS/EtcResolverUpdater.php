<?php

declare(strict_types=1);

namespace Zored\Dmns\Host\MacOS;

use League\Flysystem\FilesystemInterface;
use Zored\Dmns\Host\DnsUpdaterInterface;
use Zored\Dmns\Model\Address;

class EtcResolverUpdater implements DnsUpdaterInterface
{
    /** @var FilesystemInterface */
    private $fs;

    public function __construct(FilesystemInterface $fs)
    {
        $this->fs = $fs;
    }

    public function update(Address $address, string $tld): bool
    {
        return $this->fs->put($this->getPath($tld), $this->getContent($address));
    }

    private function getContent(Address $address): string
    {
        return <<<RESOLVER
nameserver {$address->getHost()}
port {$address->getPort()}
RESOLVER;
    }

    private function getPath(string $tld): string
    {
        return "/etc/resolver/$tld";
    }
}
