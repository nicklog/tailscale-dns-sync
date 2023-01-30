<?php

declare(strict_types=1);

namespace App\Client\Cloudflare\Model;

final readonly class Zone
{
    public function __construct(
        public string $id,
        public string $name,
        public string $status,
        public string $type,
    ) {
    }
}
