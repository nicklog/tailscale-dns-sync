<?php

declare(strict_types=1);

namespace App\Command;

use App\Client\Cloudflare\CloudflareClient;
use App\Client\Cloudflare\Collection\DnsRecords;
use App\Client\Cloudflare\Request\ZoneDnsRecordDeleteRequest;
use App\Client\Cloudflare\Request\ZoneDnsRecordsRequest;
use App\Client\Cloudflare\Request\ZonesRequest;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

use function sprintf;

#[AsCommand('delete-all')]
final class DeleteAllCommand extends Command
{
    private SymfonyStyle $io;

    public function __construct(
        private readonly CloudflareClient $cloudflareClient,
        private string $domain,
    ) {
        parent::__construct();
    }

    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        $this->io = new SymfonyStyle($input, $output);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $cloudflareZones = $this->cloudflareClient->getZones(new ZonesRequest());

        $zone = $cloudflareZones->getZones()->getByName($this->domain);

        $dnsRecords = $this->cloudflareClient->getZoneDnsRecords(new ZoneDnsRecordsRequest($zone->id))->getDnsRecords();
        $dnsRecords = $dnsRecords->selectByTypes('A', 'AAAA');

        $this->deleteRecords($dnsRecords);

        return Command::SUCCESS;
    }

    private function deleteRecords(DnsRecords $dnsRecords): void
    {
        foreach ($dnsRecords as $dnsRecord) {
            $this->cloudflareClient->deleteZoneDnsRecord(new ZoneDnsRecordDeleteRequest($dnsRecord->zoneId, $dnsRecord->id));
            $this->io->writeln(sprintf('Delete DNS record %s from type %s with name %s', $dnsRecord->id, $dnsRecord->type, $dnsRecord->name));
        }
    }
}
