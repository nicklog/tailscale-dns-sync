<?php

declare(strict_types=1);

namespace App\Client\Cloudflare\Model;

use DateTimeInterface;
use Symfony\Component\Serializer\Annotation\SerializedName;

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
}
