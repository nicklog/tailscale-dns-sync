<?php

declare(strict_types=1);

namespace App\Client\Cloudflare\Collection;

use App\Client\Cloudflare\Model\Zone;
use App\Infrastructure\Collection\Collection;

/** @template-extends Collection<int, Zone> */
final class Zones extends Collection
{
    public function getByName(string $name): Zone
    {
        return $this
            ->filter(static fn (Zone $zone): bool => $zone->name === $name)
            ->first();
    }
}
