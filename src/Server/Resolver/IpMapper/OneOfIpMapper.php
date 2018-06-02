<?php
declare(strict_types=1);

namespace Zored\Dmns\Server\Resolver\IpMapper;

class OneOfIpMapper implements IpMapperInterface
{
    /** @var IpMapperInterface[] */
    private $ipMappers;

    public function __construct(IpMapperInterface ...$ipMappers)
    {
        $this->ipMappers = $ipMappers;
    }

    public function getIp(string $domain): ?string
    {
        foreach ($this->ipMappers as $mapper) {
            $ip = $mapper->getIp($domain);
            if ($ip !== null) {
                return $ip;
            }
        }

        return null;
    }

}