<?php

declare(strict_types=1);

namespace App\Client\Tailscale\Response;

use App\Client\Tailscale\Collection\Devices;
use App\Client\Tailscale\Model\Device;
use ArrayIterator;
use IteratorAggregate;

/** @template-implements  IteratorAggregate<int, Device> */
final readonly class DevicesResponse implements IteratorAggregate
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

    /** @return ArrayIterator<int, Device> */
    public function getIterator(): ArrayIterator
    {
        return $this->getDevices()->getIterator();
    }
}
