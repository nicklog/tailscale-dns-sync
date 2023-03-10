<?php

declare(strict_types=1);

namespace App\Client\Cloudflare\Response;

use App\Client\Cloudflare\Collection\DnsRecords;
use App\Client\Cloudflare\Model\DnsRecord;
use ArrayIterator;
use IteratorAggregate;
use Symfony\Component\Serializer\Annotation\SerializedName;

/** @template-implements  IteratorAggregate<int, DnsRecord> */
final class ZoneDnsRecordsResponse implements IteratorAggregate
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

    /** @return ArrayIterator<int, DnsRecord> */
    public function getIterator(): ArrayIterator
    {
        return $this->getDnsRecords()->getIterator();
    }
}
