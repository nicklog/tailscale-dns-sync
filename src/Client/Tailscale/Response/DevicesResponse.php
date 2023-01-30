<?php

declare(strict_types=1);

namespace App\Client\Tailscale\Response;

use App\Client\Tailscale\Model\Device;

final readonly class DevicesResponse
{
    /** @param list<Device> $devices */
    public function __construct(
        public array $devices,
    ) {
    }
}
