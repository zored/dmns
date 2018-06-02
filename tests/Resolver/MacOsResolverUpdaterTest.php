<?php

declare(strict_types=1);

namespace Test\Zored\Dmns\Resolver;

use League\Flysystem\FilesystemInterface;
use PHPUnit\Framework\TestCase;
use Zored\Dmns\Host\MacOS\EtcResolverUpdater;
use Zored\Dmns\Model\Address;

class MacOsResolverUpdaterTest extends TestCase
{
    private $fs;

    private $resolver;

    protected function setUp(): void
    {
        $this->resolver = new EtcResolverUpdater(
            $this->fs = $this->createMock(FilesystemInterface::class)
        );
    }

    public function testCreate(): void
    {
        $address = $this->createMock(Address::class);
        $address
            ->expects($this->once())
            ->method('getHost')
            ->with()
            ->willReturn($host = '127.0.0.1');
        $address
            ->expects($this->once())
            ->method('getPort')
            ->with()
            ->willReturn($port = 123);
        $tld = 'com';
        $this->fs
            ->expects($this->once())
            ->method('put')
            ->with('/etc/resolver/com', <<<RESOLVER
nameserver 127.0.0.1
port 123
RESOLVER
            )
            ->willReturn(true);
        $this->assertTrue($this->resolver->update($address, $tld));
    }
}
