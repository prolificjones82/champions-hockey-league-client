<?php

namespace ChampionsLeagueClient\Client;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use ChampionsLeagueClient\Interfaces\ConnectionInterface;

abstract class Client extends AbstractClient implements ConnectionInterface
{
    public function __construct(?array $headers = [])
    {
        parent::__construct($headers);
        $this->getLogger()->addInfo(sprintf('CHL SDK: %s', static::class), [
            'base_url' => $this->getBaseUrl(),
            'headers' => $this->getHeaders(),
            'logger' => get_class($this->getLogger()),
            'client' => get_class($this->getClient()),
            'client_options' => $this->getClientOptions(),
        ]);
    }

    public function makeRequest(string $method, string $path, ?array $data = [], ?array $headers = []): Response
    {
        $url = sprintf('%s/%s', $this->getBaseUrl(), ltrim($path, '/'));
        $caller = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2)[1] ?? [];
        $this->getLogger()->addInfo(
            sprintf('CHL SDK - (%s):', $caller['function']),
            [
                'url' => $url,
                'method' => $method,
                'data' => $data,
                'headers' => $headers,
            ],
        );

        $response = $this->getClient()->request($method, $url, $this->getClientOptions());

        return new JsonResponse(
            json_decode($response->getBody()),
            $response->getStatusCode(),
            $response->getHeaders(),
        );
    }
}
