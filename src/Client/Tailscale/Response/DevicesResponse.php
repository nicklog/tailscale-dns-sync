<?php

declare(strict_types=1);

namespace App\Client\Tailscale\Response;

use App\Client\Tailscale\Collection\Devices;
use App\Client\Tailscale\Model\Device;

final readonly class DevicesResponse
{
    /** @param list<Device> $devices */
    public function __construct(
        private array $devices,
    ) {
    }

    public function getDevices(): Devices
    {
        return new Devices($this->devices);
    }
}
