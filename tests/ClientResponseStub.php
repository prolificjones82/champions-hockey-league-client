<?php

declare(strict_types=1);

use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ConnectException;
use ChampionsLeagueClient\Client\AbstractClient;

final class ClientResponseStub
{
    public static function set(AbstractClient $client, array $body, int $status = 200): void
    {
        $property = new ReflectionProperty(AbstractClient::class, 'client');
        $property->setValue(
            $client,
            new GuzzleClient([
                'handler' => HandlerStack::create(new MockHandler([
                    new Response($status, [], json_encode($body, JSON_THROW_ON_ERROR)),
                ])),
            ]),
        );
    }

    public static function failWithConnectionError(AbstractClient $client): void
    {
        $property = new ReflectionProperty(AbstractClient::class, 'client');
        $property->setValue(
            $client,
            new GuzzleClient([
                'handler' => HandlerStack::create(new MockHandler([
                    new ConnectException('Connection refused', new Request('GET', 'https://example.test')),
                ])),
            ]),
        );
    }

    public static function inTemporaryDirectory(callable $callback): void
    {
        $originalDirectory = getcwd();
        if ($originalDirectory === false) {
            throw new RuntimeException('Unable to determine the current directory.');
        }

        $directory = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'elite-league-client-' . bin2hex(random_bytes(4));
        if (!mkdir($directory)) {
            throw new RuntimeException(sprintf('Unable to create temporary directory "%s".', $directory));
        }

        chdir($directory);
        try {
            $callback();
        } finally {
            chdir($originalDirectory);
            foreach (glob($directory . DIRECTORY_SEPARATOR . '*') ?: [] as $file) {
                if (is_file($file)) {
                    unlink($file);
                }
            }
            rmdir($directory);
        }
    }
}
