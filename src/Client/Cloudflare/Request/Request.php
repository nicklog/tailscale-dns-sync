<?php

declare(strict_types=1);

namespace App\Client\Cloudflare\Request;

abstract readonly class Request
{
    abstract public function getMethod(): string;

    abstract public function getUrl(): string;

    /** @return array<string, mixed>|null */
    public function getBody(): array|null
    {
        return null;
    }
}
