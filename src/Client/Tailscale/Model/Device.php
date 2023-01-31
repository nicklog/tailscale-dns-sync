<?php

declare(strict_types=1);

namespace App\Client\Tailscale\Model;

use App\Client\Tailscale\Collection\Addresses;
use App\Client\Tailscale\Collection\Tags;
use DateTimeInterface;

final readonly class Device
{
    /**
     * @param list<string> $addresses
     * @param list<string> $tags
     */
    public function __construct(
        public string $id,
        public string $name,
        public string $hostname,
        public string $machineKey,
        public string $nodeId,
        public string $nodeKey,
        public string $os,
        public string $tailnetLockError,
        public string $tailnetLockKey,
        public string $user,
        public string $clientVersion,
        private array $addresses,
        private array $tags,
        public bool $authorized,
        public bool $blocksIncomingConnections,
        public bool $isExternal,
        public bool $keyExpiryDisabled,
        public DateTimeInterface $created,
        public DateTimeInterface $expires,
        public DateTimeInterface $lastSeen,
    ) {
    }

    public function getAddresses(): Addresses
    {
        return Addresses::fromStrings(...$this->addresses);
    }

    public function getTags(): Tags
    {
        return new Tags($this->tags);
    }
}
