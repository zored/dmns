<?php

declare(strict_types=1);

namespace Tests\Zored\Dmns\Command;

use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Zored\Dmns\Command\Extractor\AddressExtractor;
use Zored\Dmns\Command\HostResolverUpdateCommand;
use Zored\Dmns\Host\DnsUpdaterInterface;
use Zored\Dmns\Model\Address;

class HostResolverUpdateCommandTest extends TestCase
{
    private $command;

    private $resolverUpdate;

    private $addressExtractor;

    protected function setUp(): void
    {
        $this->command = new HostResolverUpdateCommand(
            $this->addressExtractor = $this->createMock(AddressExtractor::class),
            $this->resolverUpdate = $this->createMock(DnsUpdaterInterface::class),
            $this->createMock(LoggerInterface::class)
        );
    }

    public function testSuccess(): void
    {
        $input = $this->createMock(InputInterface::class);
        $output = $this->createMock(OutputInterface::class);

        $this->addressExtractor
            ->expects($this->once())
            ->method('extract')
            ->with($input)
            ->willReturn($address = $this->createMock(Address::class));
        $input
            ->expects($this->once())
            ->method('getOption')
            ->with('tld')
            ->willReturn($tld = 'com');
        $this->resolverUpdate
            ->expects($this->once())
            ->method('update')
            ->with($address, $tld)
            ->willReturn(true);

        /** @see HostResolverUpdateCommand::execute() */
        $this->assertSame(0, $this->command->run($input, $output));
    }

    public function testErrorRun(): void
    {
        $this->resolverUpdate->method('update')->willReturn(false);
        $this->assertSame(1, $this->command->run(
            $this->createMock(InputInterface::class),
            $this->createMock(OutputInterface::class)
        ));
    }
}
