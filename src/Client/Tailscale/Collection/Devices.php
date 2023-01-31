<?php

declare(strict_types=1);

namespace App\Client\Tailscale\Collection;

use App\Client\Tailscale\Model\Device;
use App\Infrastructure\Collection\Collection;

/** @template-extends Collection<int, Device> */
final class Devices extends Collection
{
}
