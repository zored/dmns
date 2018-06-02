<?php
declare(strict_types=1);

namespace Zored\Dmns\Server\Resolver\IpMapper;


class DockerMachineIpMapper implements IpMapperInterface
{
    /** @var array */
    private $aliases;

    public function __construct(array $aliases = [])
    {
        $this->aliases = $aliases;
    }

    public function getIp(string $domain): ?string
    {
        $machine = $this->getMachine($domain);

        return $this->getIpByMachine($machine);
    }

    private function getMachine(string $domain): string
    {
        $machine = preg_replace('/\.[^\.]+$/', '', $domain);

        return $this->aliases[$machine] ?? $machine;
    }

    private function getIpByMachine(string $machine): ?string
    {
        $machine = \escapeshellarg($machine);
        exec("docker-machine ip $machine", $output, $exitCode);
        if ($exitCode !== 0) {
            return null;
        }

        return implode('', $output);
    }
}