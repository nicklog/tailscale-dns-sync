<?php

declare(strict_types=1);

namespace App\Client\Tailscale\Collection;

use App\Infrastructure\Collection\Collection;
use IPLib\Address\AddressInterface;
use IPLib\Factory;

use function array_filter;
use function array_map;

/** @template-extends Collection<int, AddressInterface> */
class Addresses extends Collection
{
    public static function fromStrings(string ...$addresses): self
    {
        $addresses = array_map(
            static fn (string $address): AddressInterface|null => Factory::parseAddressString($address),
            $addresses,
        );

        return new self(array_filter($addresses));
    }
}
