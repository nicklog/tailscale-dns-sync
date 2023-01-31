<?php

declare(strict_types=1);

namespace App\Client\Cloudflare\Request;

use function sprintf;

final readonly class ZoneDnsRecordUpdateRequest extends Request
{
    public function __construct(
        private string $zoneId,
        private string $dnsRecordId,
        private string $type,
        private string $name,
        private string $content,
        private int $timeToLive = 1,
    ) {
    }

    public function getMethod(): string
    {
        return 'PUT';
    }

    public function getUrl(): string
    {
        return sprintf(
            'https://api.cloudflare.com/client/v4/zones/%s/dns_records/%s',
            $this->zoneId,
            $this->dnsRecordId,
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
