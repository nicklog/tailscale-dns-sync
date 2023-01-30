<?php

declare(strict_types=1);

namespace App\Client\Cloudflare\Request;

use function sprintf;

final readonly class ZoneDnsRecordCreateRequest extends Request
{
    public function __construct(
        private string $zoneId,
        private string $type,
        private string $name,
        private string $content,
        private int $timeToLive = 120,
    ) {
    }

    public function getMethod(): string
    {
        return 'POST';
    }

    public function getUrl(): string
    {
        return sprintf(
            'https://api.cloudflare.com/client/v4/zones/%s/dns_records',
            $this->zoneId,
        );
    }

    public function getBody(): array|null
    {
        return [
            'type'    => $this->type,
            'name'    => $this->name,
            'content' => $this->content,
            'ttl'     => $this->timeToLive,
        ];
    }
}
