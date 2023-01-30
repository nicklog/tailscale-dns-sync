<?php

declare(strict_types=1);

namespace App\Client\Cloudflare\Response;

use App\Client\Cloudflare\Collection\DnsRecords;
use App\Client\Cloudflare\Model\DnsRecord;
use Symfony\Component\Serializer\Annotation\SerializedName;

final class ZoneDnsRecordsResponse
{
    /** @param list<DnsRecord> $dnsRecords */
    public function __construct(
        #[SerializedName('result')]
        private array $dnsRecords,
    ) {
    }

    public function getDnsRecords(): DnsRecords
    {
        return new DnsRecords($this->dnsRecords);
    }
}
