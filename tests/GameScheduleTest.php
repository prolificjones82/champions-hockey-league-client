<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use ChampionsLeagueClient\Schedule\GameSchedule;
use ChampionsLeagueClient\Schedule\Response\ScheduleResponse;

final class GameScheduleTest extends TestCase
{
    private bool $hadDefaultTeamId;
    private mixed $originalDefaultTeamId;

    public function testGetTeamSchedule(): void
    {
        $client = new GameSchedule();
        ClientResponseStub::set($client, ['matches' => []]);
        $response = $client->getTeamSchedule(2025);
        $this->assertInstanceOf(ScheduleResponse::class, $response);
        $this->assertSame([], $response->matches);
    }

    public function testTeamScheduleHttpFailureIsWrapped(): void
    {
        ClientResponseStub::inTemporaryDirectory(function (): void {
            $client = new GameSchedule();
            ClientResponseStub::set($client, [], 404);

            try {
                $client->getTeamSchedule(2025);
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
                $client->getTeamSchedule(2025);
                self::fail('Expected a connection failure.');
            } catch (ConnectException) {
                self::assertTrue(true);
            }

            ClientResponseStub::failWithConnectionError($client);
            $this->expectException(ConnectException::class);
            $client->getTeamSchedule(2025);
        });
    }

    protected function setUp(): void
    {
        $key = 'CHAMPIONS_HOCKEY_LEAGUE_DEFAULT_TEAM_ID';
        $this->hadDefaultTeamId = array_key_exists($key, $_ENV);
        $this->originalDefaultTeamId = $_ENV[$key] ?? null;
        $_ENV[$key] = '73';
    }

    protected function tearDown(): void
    {
        $key = 'CHAMPIONS_HOCKEY_LEAGUE_DEFAULT_TEAM_ID';
        if ($this->hadDefaultTeamId) {
            $_ENV[$key] = $this->originalDefaultTeamId;
            return;
        }

        unset($_ENV[$key]);
    }
}
