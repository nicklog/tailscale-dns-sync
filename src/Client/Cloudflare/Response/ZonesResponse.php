<?php

declare(strict_types=1);

namespace App\Client\Cloudflare\Response;

use App\Client\Cloudflare\Model\Zone;
use Symfony\Component\Serializer\Annotation\SerializedName;

final readonly class ZonesResponse
{
    /** @param list<Zone> $zones */
    public function __construct(
        #[SerializedName('result')]
        public array $zones,
    ) {
    }
}
