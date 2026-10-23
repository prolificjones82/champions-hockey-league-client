<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use ChampionsLeagueClient\Schedule\Enums\League;
use ChampionsLeagueClient\Schedule\GameSchedule;
use ChampionsLeagueClient\Schedule\Response\ScheduleResponse;

final class GameScheduleTest extends TestCase
{
    public function testGetTeamSchedule(): void
    {
        $client = new GameSchedule();
        $team = (string) ($_ENV['CHAMPIONS_HOCKEY_LEAGUE_DEFAULT_TEAM_ID'] ?? 96);

        foreach (League::cases() as $league) {
            ClientResponseStub::set($client, ['matches' => []]);
            $response = $client->getTeamSchedule($league, 2025, $team);
            $this->assertInstanceOf(ScheduleResponse::class, $response);
            $this->assertSame([], $response->matches);
        }
    }

    public function testTeamScheduleHttpFailureIsWrapped(): void
    {
        ClientResponseStub::inTemporaryDirectory(function (): void {
            $client = new GameSchedule();
            ClientResponseStub::set($client, [], 404);

            try {
                $client->getTeamSchedule(League::CHL, 2025, '96');
                self::fail('Expected the HTTP failure to be rethrown.');
            } catch (Exception $exception) {
                self::assertNotInstanceOf(RequestException::class, $exception);
                self::assertSame(404, $exception->getCode());
            }
        });
    }

    public function testNonHttpFailuresAreLoggedAndRethrown(): void
    {
        ClientResponseStub::inTemporaryDirectory(function (): void {
            $client = new GameSchedule();
            ClientResponseStub::failWithConnectionError($client);
            try {
                $client->getTeamSchedule(League::CHL, 2025, '96');
                self::fail('Expected a connection failure.');
            } catch (ConnectException) {
                self::assertTrue(true);
            }

            ClientResponseStub::failWithConnectionError($client);
            $this->expectException(ConnectException::class);
            $client->getTeamSchedule(League::CHL, 2025, '96');
        });
    }
}
