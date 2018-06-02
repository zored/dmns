<?php
declare(strict_types=1);

namespace Tests\Zored\Dmns\Server\Resolver\IpMapper;

use Zored\Dmns\Server\Resolver\IpMapper\IpMapperInterface;
use Zored\Dmns\Server\Resolver\IpMapper\OneOfIpMapper;
use PHPUnit\Framework\TestCase;

class OneOfIpMapperTest extends TestCase
{
    public function testGetIp(): void
    {
        $domain = 'abc.com';
        $ip = '123.123.123.123';
        $mapper1 = $this->createMock(IpMapperInterface::class);
        $mapper2 = $this->createMock(IpMapperInterface::class);
        $mapper2
            ->expects($this->once())
            ->method('getIp')
            ->with($domain)
            ->willReturn($ip);
        (new OneOfIpMapper($mapper1, $mapper2))->getIp($domain);
    }
}
