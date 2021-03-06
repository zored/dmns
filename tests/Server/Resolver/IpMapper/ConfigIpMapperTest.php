<?php

declare(strict_types=1);

namespace Tests\Zored\Dmns\Server\Resolver\IpMapper;

use PHPUnit\Framework\TestCase;
use Zored\Dmns\Server\Resolver\IpMapper\ConfigIpMapper;

class ConfigIpMapperTest extends TestCase
{
    public function testMapping(): void
    {
        $domain = 'abc.com';
        $mapper = new ConfigIpMapper([$domain => $ip = '123.123.123.123']);
        $this->assertSame($ip, $mapper->getIp($domain));
        $this->assertNull($mapper->getIp('unknown.com'));
    }
}
