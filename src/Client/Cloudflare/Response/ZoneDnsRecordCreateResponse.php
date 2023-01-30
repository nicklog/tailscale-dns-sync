<?php

declare(strict_types=1);

namespace App\Client\Cloudflare\Response;

use App\Client\Cloudflare\Model\DnsRecord;
use Symfony\Component\Serializer\Annotation\SerializedName;

final class ZoneDnsRecordCreateResponse
{
    public function __construct(
        #[SerializedName('result')]
        public DnsRecord $record,
    ) {
    }
}
