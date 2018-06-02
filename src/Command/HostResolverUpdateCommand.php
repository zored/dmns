<?php
declare(strict_types=1);

namespace Zored\Dmns\Command;


use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Zored\Dmns\Command\Extractor\AddressExtractor;
use Zored\Dmns\Host\DnsUpdaterInterface;

class HostResolverUpdateCommand extends Command
{
    protected static $defaultName = 'host:dns:update';

    /** @var DnsUpdaterInterface */
    private $dnsUpdater;

    /** @var AddressExtractor */
    private $addressExtractor;

    /** @var LoggerInterface */
    private $logger;

    public function __construct(AddressExtractor $addressExtractor, DnsUpdaterInterface $resolverUpdater, LoggerInterface $logger)
    {
        parent::__construct();
        $this->dnsUpdater = $resolverUpdater;
        $this->addressExtractor = $addressExtractor;
        $this->logger = $logger;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Update host DNS.')
            ->setHelp(<<<HELP
Update resolver on host machine (MacOS currently).

For example, it tells your system that `*.tld` is handled on specific port `:10053`.
HELP
            )
            ->addOption(
                'host',
                'o',
                InputOption::VALUE_REQUIRED,
                'DNS server host.',
                DefaultsEnum::HOST
            )
            ->addOption(
                'port',
                'p',
                InputOption::VALUE_OPTIONAL,
                'DNS server port.',
                DefaultsEnum::PORT
            )
            ->addOption(
                'tld',
                't',
                InputOption::VALUE_REQUIRED,
                'DNS server TLD (Top Level Domain: "com" / "net")',
                DefaultsEnum::TLD
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $address = $this->addressExtractor->extract($input);
        $tld = (string) $input->getOption('tld');

        $this->logger->info("Telling that resolver for `*.$tld` is on `$address`...");
        $success = $this->dnsUpdater->update($address, $tld);

        if (!$success) {
            $this->logger->error('Could not update resolver (no permissions?)');
            return 1;
        }

        return 0;
    }


}