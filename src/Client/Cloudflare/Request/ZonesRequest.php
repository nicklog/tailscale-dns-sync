<?php

declare(strict_types=1);

namespace App\Client\Cloudflare\Request;

final readonly class ZonesRequest extends Request
{
    public function getMethod(): string
    {
        return 'GET';
    }

    public function getUrl(): string
    {
        return 'https://api.cloudflare.com/client/v4/zones';
    }
}
