<?php
declare(strict_types=1);

namespace Zored\Dmns\Server\Resolver\IpMapper;


class ConfigIpMapper implements IpMapperInterface
{
    /** @var array */
    private $ipsByDomains;

    public function __construct(array $ipsByDomains)
    {
        $this->ipsByDomains = $ipsByDomains;
    }

    public function getIp(string $domain): ?string
    {
        return $this->ipsByDomains[$domain] ?? null;
    }
}