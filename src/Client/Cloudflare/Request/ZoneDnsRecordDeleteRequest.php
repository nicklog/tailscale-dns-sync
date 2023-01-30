<?php

declare(strict_types=1);

namespace App\Client\Cloudflare\Request;

use function sprintf;

final readonly class ZoneDnsRecordDeleteRequest extends Request
{
    public function __construct(
        private string $zoneId,
        private string $dnsRecordId,
    ) {
    }

    public function getMethod(): string
    {
        return 'DELETE';
    }

    public function getUrl(): string
    {
        return sprintf(
            'https://api.cloudflare.com/client/v4/zones/%s/dns_records/%s',
            $this->zoneId,
            $this->dnsRecordId,
        );
    }
}
