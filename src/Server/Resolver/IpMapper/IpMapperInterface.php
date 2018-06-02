<?php
declare(strict_types=1);

namespace Zored\Dmns\Server\Resolver\IpMapper;


interface IpMapperInterface
{
    public function getIp(string $domain): ?string;
}