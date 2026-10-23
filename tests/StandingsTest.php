<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use ChampionsLeagueClient\Standings\Standings;
use ChampionsLeagueClient\Schedule\Enums\League;
use ChampionsLeagueClient\Standings\Response\StandingsResponse;

final class StandingsTest extends TestCase
{
    public function testGetStandings(): void
    {
        $client = new Standings();

        foreach (League::cases() as $league) {
            ClientResponseStub::set($client, ['clubs' => []]);
            $response = $client->getStandings($league, 2025);
            $this->assertInstanceOf(StandingsResponse::class, $response);
            $this->assertSame([], $response->clubs);
        }
    }

    public function testHttpFailureIsWrapped(): void
    {
        ClientResponseStub::inTemporaryDirectory(function (): void {
            $client = new Standings();
            ClientResponseStub::set($client, [], 404);

            try {
                $client->getStandings(League::CHL, 2025);
                self::fail('Expected the HTTP failure to be rethrown.');
            } catch (Exception $exception) {
                self::assertNotInstanceOf(RequestException::class, $exception);
                self::assertNotSame('', $exception->getMessage());
            }
        });
    }

    public function testNonHttpFailureIsLoggedAndRethrown(): void
    {
        ClientResponseStub::inTemporaryDirectory(function (): void {
            $client = new Standings();
            ClientResponseStub::failWithConnectionError($client);

            $this->expectException(ConnectException::class);
            $client->getStandings(League::CHL, 2025);
        });
    }
}
