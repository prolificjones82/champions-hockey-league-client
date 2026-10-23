<?php

namespace ChampionsLeagueClient\Client;

use ChampionsLeagueClient\Logger;
use ChampionsLeagueClient\Constants;
use GuzzleHttp\Client as GuzzleClient;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractClient
{
    private GuzzleClient $client;
    private string $baseUrl;
    private array $headers = [];
    private Logger $logger;
    private array $clientOptions;

    public function __construct(?array $headers)
    {
        $this->createLogger();

        $this->setBaseUrl(Constants::BASE_URL);
        $this->setHeaders(array_merge([
            'Cache-Control' => 'no-cache',
            'Content-Type' => Constants::CONTENT_TYPE,
        ], $headers ?? []));

        $this->client = new GuzzleClient();

        $this->clientOptions = [
            'debug' => false,
            'headers' => $this->getHeaders(),
            'idn_conversion' => false,
        ];
    }

    private function createLogger(): void
    {
        $this->logger = new Logger();
    }

    protected function getResponseData(Response $response, bool $asArray = true): array
    {
        return json_decode(
            $response->getContent(),
            $asArray,
            512,
            JSON_THROW_ON_ERROR,
        );
    }

    /**
     * Get the value of baseUrl
     */
    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    /**
     * Set the value of baseUrl
     *
     * @return self
     */
    public function setBaseUrl(string $baseUrl)
    {
        $this->baseUrl = $baseUrl;

        return $this;
    }

    /**
     * Get the value of headers
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * Set the value of headers
     *
     * @return self
     */
    public function setHeaders(array $headers = [])
    {
        $this->headers = array_merge($this->headers, $headers);

        return $this;
    }

    public function getLogger(): Logger
    {
        return $this->logger;
    }

    /**
     * Get the value of client
     */
    public function getClient(): GuzzleClient
    {
        return $this->client;
    }

    /**
     * Get the value of clientOptions
     */
    public function getClientOptions(array $body = []): array
    {
        if (!empty($body)) {
            $this->clientOptions['body'] = json_encode($body, JSON_THROW_ON_ERROR);
        }

        return $this->clientOptions;
    }
}
