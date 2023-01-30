<?php

declare(strict_types=1);

namespace App\Client\Cloudflare\Request;

use function sprintf;

final readonly class ZoneDnsRecordsRequest extends Request
{
    public function __construct(
        private string $zoneId,
    ) {
    }

    public function getMethod(): string
    {
        return 'GET';
    }

    public function getUrl(): string
    {
        return sprintf('https://api.cloudflare.com/client/v4/zones/%s/dns_records', $this->zoneId);
    }
}
