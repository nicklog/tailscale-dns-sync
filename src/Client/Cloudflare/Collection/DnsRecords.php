<?php

declare(strict_types=1);

namespace App\Client\Cloudflare\Collection;

use App\Client\Cloudflare\Model\DnsRecord;
use App\Infrastructure\Collection\Collection;

use function in_array;

/** @template-extends Collection<int, DnsRecord> */
final class DnsRecords extends Collection
{
    public function selectByName(string $name): self
    {
        return $this->filter(static fn (DnsRecord $record): bool => $record->name === $name);
    }

    public function selectByTypes(string ...$types): self
    {
        return $this->filter(static fn (DnsRecord $record): bool => in_array($record->type, $types, true));
    }
}
