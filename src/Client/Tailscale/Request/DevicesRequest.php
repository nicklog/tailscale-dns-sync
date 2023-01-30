<?php

declare(strict_types=1);

namespace App\Client\Tailscale\Request;

final readonly class DevicesRequest
{
    public function getMethod(): string
    {
        return 'GET';
    }

    public function getUrl(): string
    {
        return 'https://api.tailscale.com/api/v2/tailnet/{tailnet}/devices';
    }
}
