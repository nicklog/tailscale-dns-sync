<?php

declare(strict_types=1);

namespace App\Client\Cloudflare\Collection;

use App\Client\Cloudflare\Model\DnsRecord;
use App\Infrastructure\Collection\Collection;

/** @template-extends Collection<int, DnsRecord> */
final class DnsRecords extends Collection
{
    public function selectByName(string $name): self
    {
        return $this->filter(static fn (DnsRecord $record): bool => $record->name === $name);
    }

    public function selectByType(string $type): self
    {
        return $this->filter(static fn (DnsRecord $record): bool => $record->type === $type);
    }
}
