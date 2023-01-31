<?php

declare(strict_types=1);

namespace App\Client\Cloudflare\Model;

use DateTimeInterface;
use IPLib\Address\AddressInterface;
use IPLib\Factory;
use Symfony\Component\Serializer\Annotation\SerializedName;

use function in_array;

final readonly class DnsRecord
{
    /** @param list<string> $tags */
    public function __construct(
        public string $id,
        #[SerializedName('zone_id')]
        public string $zoneId,
        #[SerializedName('zone_name')]
        public string $zoneName,
        public string $name,
        public string $type,
        public string $content,
        public bool $proxiable,
        public bool $proxied,
        public bool $locked,
        public int $ttl,
        public string|null $comment,
        public array $tags,
        #[SerializedName('created_on')]
        public DateTimeInterface $createdOn,
        #[SerializedName('modified_on')]
        public DateTimeInterface $modifiedOn,
    ) {
    }

    public function isIpZone(): bool
    {
        $ipTypes = ['A', 'AAAA'];

        return in_array($this->type, $ipTypes, true);
    }

    public function getAddress(): AddressInterface|null
    {
        return Factory::parseAddressString($this->content);
    }
}
