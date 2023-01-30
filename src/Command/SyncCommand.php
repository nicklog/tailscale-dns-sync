<?php

declare(strict_types=1);

namespace App\Command;

use App\Client\Cloudflare\CloudflareClient;
use App\Client\Cloudflare\Request\ZoneDnsRecordCreateRequest;
use App\Client\Cloudflare\Request\ZoneDnsRecordDeleteRequest;
use App\Client\Cloudflare\Request\ZoneDnsRecordsRequest;
use App\Client\Cloudflare\Request\ZonesRequest;
use App\Client\Tailscale\Request\DevicesRequest;
use App\Client\Tailscale\TailscaleClient;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand('sync')]
final class SyncCommand extends Command
{
    public function __construct(
        private readonly TailscaleClient $tailscaleClient,
        private readonly CloudflareClient $cloudflareClient,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $response = $this->tailscaleClient->getDevices(new DevicesRequest());
        $response = $this->cloudflareClient->getZones(new ZonesRequest());
        $response = $this->cloudflareClient->getZoneDnsRecords(new ZoneDnsRecordsRequest('8a03978779bc76baf7937963a65bc8fc'));
        $response = $this->cloudflareClient->createZoneDnsRecord(new ZoneDnsRecordCreateRequest(
            '8a03978779bc76baf7937963a65bc8fc',
            'A',
            'test.test.test',
            '192.168.0.50',
        ));
        $response = $this->cloudflareClient->deleteZoneDnsRecord(new ZoneDnsRecordDeleteRequest(
            '8a03978779bc76baf7937963a65bc8fc',
            'ae6ac10983a9369155b00fc7f57208ad',
        ));

        return Command::SUCCESS;
    }
}
