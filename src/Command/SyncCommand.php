<?php

declare(strict_types=1);

namespace App\Command;

use App\Client\Cloudflare\CloudflareClient;
use App\Client\Tailscale\Request\DevicesRequest;
use App\Client\Tailscale\TailscaleClient;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use function dd;

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

        dd($response->getDevices()->first()->getAddresses());

        return Command::SUCCESS;
    }
}
