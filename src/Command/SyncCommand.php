<?php

declare(strict_types=1);

namespace App\Command;

use App\Client\Cloudflare\CloudflareClient;
use App\Client\Cloudflare\Collection\DnsRecords;
use App\Client\Cloudflare\Model\DnsRecord;
use App\Client\Cloudflare\Request\ZoneDnsRecordCreateRequest;
use App\Client\Cloudflare\Request\ZoneDnsRecordDeleteRequest;
use App\Client\Cloudflare\Request\ZoneDnsRecordsRequest;
use App\Client\Cloudflare\Request\ZoneDnsRecordUpdateRequest;
use App\Client\Cloudflare\Request\ZonesRequest;
use App\Client\Tailscale\Model\Device;
use App\Client\Tailscale\Request\DevicesRequest;
use App\Client\Tailscale\TailscaleClient;
use App\Infrastructure\Collection\Collection;
use IPLib\Address\AddressInterface;
use IPLib\Factory;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

use function sprintf;

#[AsCommand('sync')]
final class SyncCommand extends Command
{
    private SymfonyStyle $io;

    public function __construct(
        private readonly TailscaleClient $tailscaleClient,
        private readonly CloudflareClient $cloudflareClient,
        private string $domain,
        private string $tailscaleNet,
    ) {
        parent::__construct();
    }

    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        $this->io = new SymfonyStyle($input, $output);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $tailscaleDevices = $this->tailscaleClient->getDevices(new DevicesRequest())->getDevices();

        $domains = $tailscaleDevices
            ->map(fn (Device $device): string => $device->getDomain($this->tailscaleNet, $this->domain));

        $zones = $this->cloudflareClient->getZones(new ZonesRequest());
        $zone  = $zones->getZones()->getByName($this->domain);

        $dnsRecords     = $this->cloudflareClient->getZoneDnsRecords(new ZoneDnsRecordsRequest($zone->id))->getDnsRecords();
        $dnsRecordsIpv4 = $dnsRecords->selectByTypes('A');

        foreach ($tailscaleDevices as $device) {
            $domainName = $device->getDomain($this->tailscaleNet, $this->domain);

            $dnsRecordsByName = $dnsRecordsIpv4->selectByName($domainName);
            $dnsRecord        = $dnsRecordsByName->isEmpty() ? null : $dnsRecordsByName->first();

            foreach ($device->getAddresses() as $address) {
                if ($address->getAddressType() !== 4) {
                    continue;
                }

                if ($dnsRecord !== null) {
                    if ($dnsRecord->content === $address->toString()) {
                        $this->io->writeln(sprintf('DNS record %s does not need an update', $dnsRecord->name));

                        continue;
                    }

                    $this->io->writeln(sprintf('Update DNS record %s to %s', $dnsRecord->name, $address));

                    $this->cloudflareClient->updateZoneDnsRecord(new ZoneDnsRecordUpdateRequest(
                        $dnsRecord->zoneId,
                        $dnsRecord->id,
                        'A',
                        $domainName,
                        $address->toString(),
                    ));

                    continue;
                }

                $this->io->writeln(sprintf('Create DNS record %s with %s', $domainName, $address));

                $this->cloudflareClient->createZoneDnsRecord(new ZoneDnsRecordCreateRequest(
                    $zone->id,
                    'A',
                    $domainName,
                    $address->toString(),
                ));
            }
        }

        $this->deleteIpv6($dnsRecords);
        $this->deleteNonExistingDomains($domains, $dnsRecords);

        return Command::SUCCESS;
    }

    private function deleteIpv6(
        DnsRecords $dnsRecords,
    ): void {
        $dnsRecords = $dnsRecords->selectByTypes('AAAA');

        $this->deleteRecords($dnsRecords);
    }

    private function deleteNonExistingDomains(
        Collection $domains,
        DnsRecords $dnsRecords,
    ): void {
        $dnsRecords = $dnsRecords->filter(function (DnsRecord $dnsRecord) use ($domains): bool {
            if (! $dnsRecord->isIpZone()) {
                return false;
            }

            $ipAddress = $dnsRecord->getAddress();
            if ($ipAddress === null) {
                return false;
            }

            if (! $this->isTailscaleIp($ipAddress)) {
                return false;
            }

            return ! $domains->has($dnsRecord->name);
        });

        $this->deleteRecords($dnsRecords);
    }

    private function deleteRecords(DnsRecords $dnsRecords): void
    {
        foreach ($dnsRecords as $dnsRecord) {
            $this->cloudflareClient->deleteZoneDnsRecord(new ZoneDnsRecordDeleteRequest($dnsRecord->zoneId, $dnsRecord->id));
            $this->io->writeln(sprintf('Delete DNS records %s from type %s with name %s', $dnsRecord->id, $dnsRecord->type, $dnsRecord->name));
        }
    }

    private function isTailscaleIp(AddressInterface $address): bool
    {
        $ipv4Network = Factory::parseRangeString('100.64.0.0/10');
        $ipv6Network = Factory::parseRangeString('fd7a:115c:a1e0::/48');

        if ($ipv4Network === null || $ipv6Network === null) {
            return false;
        }

        return $ipv4Network->contains($address) || $ipv6Network->contains($address);
    }
}
