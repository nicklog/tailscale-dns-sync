<?php

declare(strict_types=1);

namespace App\Client\Cloudflare;

use App\Client\Cloudflare\Request\Request;
use App\Client\Cloudflare\Request\ZoneDnsRecordCreateRequest;
use App\Client\Cloudflare\Request\ZoneDnsRecordDeleteRequest;
use App\Client\Cloudflare\Request\ZoneDnsRecordsRequest;
use App\Client\Cloudflare\Request\ZonesRequest;
use App\Client\Cloudflare\Response\ZoneDnsRecordCreateResponse;
use App\Client\Cloudflare\Response\ZoneDnsRecordsResponse;
use App\Client\Cloudflare\Response\ZonesResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

final class CloudflareClient
{
    public function __construct(
        private HttpClientInterface $httpClient,
        private SerializerInterface $serializer,
        private string $cloudflareKey,
    ) {
    }

    public function getZones(ZonesRequest $request): ZonesResponse
    {
        $response = $this->sendRequest($request);

        return $this->deserialize($response, ZonesResponse::class);
    }

    public function getZoneDnsRecords(ZoneDnsRecordsRequest $request): ZoneDnsRecordsResponse
    {
        $response = $this->sendRequest($request);

        return $this->deserialize($response, ZoneDnsRecordsResponse::class);
    }

    public function createZoneDnsRecord(ZoneDnsRecordCreateRequest $request): ZoneDnsRecordCreateResponse
    {
        $response = $this->sendRequest($request);

        return $this->deserialize($response, ZoneDnsRecordCreateResponse::class);
    }

    public function deleteZoneDnsRecord(ZoneDnsRecordDeleteRequest $request): bool
    {
        $response = $this->sendRequest($request);

        return $response->getStatusCode() === 200;
    }

    private function sendRequest(Request $request): ResponseInterface
    {
        $options = [
            'auth_bearer' => $this->cloudflareKey,
        ];

        if ($request->getBody() !== null) {
            $options['json'] = $request->getBody();
        }

        return $this->httpClient->request($request->getMethod(), $request->getUrl(), $options);
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
