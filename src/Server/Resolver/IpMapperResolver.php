<?php
declare(strict_types=1);

namespace Zored\Dmns\Server\Resolver;


use Psr\Log\LoggerInterface;
use yswery\DNS\RecordTypeEnum;
use yswery\DNS\ResolverInterface;
use Zored\Dmns\Server\Resolver\IpMapper\IpMapperInterface;

class IpMapperResolver implements ResolverInterface
{
    /** @var IpMapperInterface */
    private $ipMapper;

    /** @var LoggerInterface */
    private $logger;

    public function __construct(IpMapperInterface $ipMapper, LoggerInterface $logger)
    {
        $this->ipMapper = $ipMapper;
        $this->logger = $logger;
    }

    public function getAnswer(array $query): array
    {
        $items = array_map([$this, 'getItem'], $query);
        $items = array_filter($items);

        return \array_values($items);
    }

    public function allowsRecursion(): bool
    {
        return false;
    }

    public function isAuthority($domain): bool
    {
        return false;
    }

    private function getItem(array $item): ?array
    {
        $type = $item['qtype'];
        if ($type !== RecordTypeEnum::TYPE_A) {
            return null;
        }

        $name = $item['qname'];
        $domain = trim($name, '.');
        $ip = $this->ipMapper->getIp($domain);
        if (!$ip) {
            return null;
        }

        $this->logger->info("$domain -> $ip");

        return $this->formatItem($item, $name, $ip);
    }

    private function formatItem(array $item, string $name, string $ip): array
    {
        return [
            'name' => $name,
            'class' => $item['qclass'],
            'ttl' => 300,
            'data' => [
                'type' => RecordTypeEnum::TYPE_A,
                'value' => $ip,
            ],
        ];
    }
}