<?php

declare(strict_types=1);

namespace App\Client\Cloudflare\Response;

use App\Client\Cloudflare\Collection\Zones;
use App\Client\Cloudflare\Model\Zone;
use Symfony\Component\Serializer\Annotation\SerializedName;

final readonly class ZonesResponse
{
    /** @param list<Zone> $zones */
    public function __construct(
        #[SerializedName('result')]
        private array $zones,
    ) {
    }

    public function getZones(): Zones
    {
        return new Zones($this->zones);
    }
}
