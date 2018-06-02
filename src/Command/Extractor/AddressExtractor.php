<?php
declare(strict_types=1);

namespace Zored\Dmns\Command\Extractor;


use Symfony\Component\Console\Input\InputInterface;
use Zored\Dmns\Model\Address;

class AddressExtractor
{
    public function extract(InputInterface $input): Address
    {
        $host = (string) $input->getOption('host');
        $port = (int) $input->getOption('port');

        return new Address($host, $port);
    }

}