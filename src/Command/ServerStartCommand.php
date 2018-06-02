<?php
declare(strict_types=1);

namespace Zored\Dmns\Command;


use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Zored\Dmns\Command\Extractor\AddressExtractor;
use Zored\Dmns\Server\ServerManagerInterface;

class ServerStartCommand extends Command
{
    protected static $defaultName = 'server:start';

    /** @var ServerManagerInterface */
    private $serverManager;

    /** @var AddressExtractor */
    private $addressExtractor;

    /** @var LoggerInterface */
    private $logger;

    public function __construct(AddressExtractor $addressExtractor, ServerManagerInterface $serverManager, LoggerInterface $logger)
    {
        parent::__construct();
        $this->serverManager = $serverManager;
        $this->addressExtractor = $addressExtractor;
        $this->logger = $logger;
    }


    protected function configure(): void
    {
        $this
            ->setDescription('Start DNS server.')
            ->addOption(
                'host',
                'o',
                InputOption::VALUE_OPTIONAL,
                'Hostname.',
                DefaultsEnum::SERVER_HOST
            )
            ->addOption(
                'port',
                'p',
                InputOption::VALUE_OPTIONAL,
                'Port.',
                DefaultsEnum::PORT
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $address = $this->addressExtractor->extract($input);
        $this->logger->info("Listening DNS-requests on $address");

        $this->serverManager->listen($address);
    }
}