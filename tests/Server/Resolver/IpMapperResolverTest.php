<?php
declare(strict_types=1);

namespace Tests\Zored\Dmns\Server\Resolver;

use Psr\Log\LoggerInterface;
use yswery\DNS\RecordTypeEnum;
use Zored\Dmns\Server\Resolver\IpMapper\IpMapperInterface;
use Zored\Dmns\Server\Resolver\IpMapperResolver;
use PHPUnit\Framework\TestCase;

class IpMapperResolverTest extends TestCase
{
    private $resolver;

    private $mapper;

    protected function setUp(): void
    {
        $this->resolver = new IpMapperResolver(
            $this->mapper = $this->createMock(IpMapperInterface::class),
            $this->createMock(LoggerInterface::class)
        );
    }

    public function testGetAnswer(): void
    {
        $this->mapper
            ->expects($this->exactly(2))
            ->method('getIp')
            ->withConsecutive(['abc.com'], ['def.net'])
            ->willReturnOnConsecutiveCalls(null, $ip = '123.123.123.123');

        $result = $this->resolver->getAnswer([
            // Wrong type:
            [
                'qtype' => RecordTypeEnum::TYPE_AAAA
            ],

            // Unknown name:
            [
                'qtype' => RecordTypeEnum::TYPE_A,
                'qname' => 'abc.com.',
            ],

            // Ok:
            [
                'qtype' => RecordTypeEnum::TYPE_A,
                'qname' => 'def.net.',
                'qclass' => 0x0001 // IN / Internet: http://www.zytrax.com/books/dns/ch15/#qclass
            ],
        ]);

        $this->assertSame([
            [
                'name' => 'def.net.',
                'class' => 0x0001,
                'ttl' => 300,
                'data' => [
                    'type' => RecordTypeEnum::TYPE_A,
                    'value' => $ip,
                ],
            ]
        ], $result);
    }

    public function testGetters(): void
    {
        $this->assertFalse($this->resolver->allowsRecursion());
        $this->assertFalse($this->resolver->isAuthority('x'));
    }
}
