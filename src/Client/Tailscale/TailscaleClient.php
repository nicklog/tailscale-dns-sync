<?php

declare(strict_types=1);

namespace App\Client\Tailscale;

use App\Client\Tailscale\Request\DevicesRequest;
use App\Client\Tailscale\Response\DevicesResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

use function sprintf;
use function str_replace;

final readonly class TailscaleClient
{
    public function __construct(
        private HttpClientInterface $httpClient,
        private SerializerInterface $serializer,
        private string $tailscaleKey,
        private string $tailscaleNet,
    ) {
    }

    public function getDevices(DevicesRequest $request): DevicesResponse
    {
        $response = $this->sendRequest($request);

        return $this->deserialize($response, DevicesResponse::class);
    }

    private function sendRequest(DevicesRequest $request): ResponseInterface
    {
        $url = str_replace('{tailnet}', $this->tailscaleNet, $request->getUrl());

        return $this->httpClient->request($request->getMethod(), $url, [
            'auth_basic' => sprintf('%s:', $this->tailscaleKey),
        ]);
    }

    /**
     * @param class-string<T> $className
     *
     * @return T
     *
     * @template T of object
     */
    private function deserialize(ResponseInterface $response, string $className): object
    {
        return $this->serializer->deserialize($response->getContent(), $className, 'json');
    }
}
