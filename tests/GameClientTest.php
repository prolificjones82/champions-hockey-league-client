<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use ChampionsLeagueClient\Game\Game;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use ChampionsLeagueClient\Game\Response\GameInfoResponse;
use ChampionsLeagueClient\Game\Response\TeamStatResponse;
use ChampionsLeagueClient\Game\Response\GameEventResponse;
use ChampionsLeagueClient\Game\Response\GoalieStatResponse;
use ChampionsLeagueClient\Game\Response\PlayerStatResponse;

final class GameClientTest extends TestCase
{
    public function testGameEndpointsConvertJsonResponsesToTypedResponses(): void
    {
        $client = new Game();

        ClientResponseStub::set($client, [
            'actualTimeName' => 'Final',
            'actualTimeAlias' => 'FIN',
            'stadium' => 'Arena',
            'numberOfViewers' => 0,
            'referees' => [
                'headReferee1' => '',
                'headReferee2' => '',
                'lineReferee1' => '',
                'lineReferee2' => '',
            ],
            'teamInfo' => [
                'home' => ['id' => 1, 'name' => 'Home', 'shortcut' => 'H'],
                'visitor' => ['id' => 2, 'name' => 'Away', 'shortcut' => 'A'],
            ],
            'trainers' => ['homeHeadCoach' => '', 'visitorHeadCoach' => ''],
            'teamBilance' => ['homeWin' => 0, 'visitorWin' => 0],
            'results' => [
                'extra_time' => false,
                'shooting' => false,
                'contumacy' => false,
                'score' => [
                    'final' => ['score_home' => 0, 'score_guest' => 0],
                    'first_period' => ['score_home' => 0, 'score_guest' => 0],
                    'second_period' => ['score_home' => 0, 'score_guest' => 0],
                    'third_period' => ['score_home' => 0, 'score_guest' => 0],
                ],
            ],
            'roster' => ['home' => [], 'visitor' => []],
        ]);
        self::assertInstanceOf(GameInfoResponse::class, $client->getGameAndRoster('123'));

        ClientResponseStub::set($client, ['last_event_time' => 0]);
        self::assertInstanceOf(GameEventResponse::class, $client->getGameEvents(123));

        ClientResponseStub::set($client, [
            'id' => 1,
            'name' => 'Player',
            'firstname' => 'Player',
            'surname' => 'Example',
            'birthdate' => '2000-01-01',
            'active' => true,
            'position' => 'F',
            'stick' => 'L',
            'weight' => 80,
            'height' => 180,
            'jersey' => 9,
            'birthCountry' => 'GB',
            'nationality' => 'British',
            'nationalityShort' => 'GBR',
            'statistics' => ['teamShortcut' => 'HOM'],
        ]);
        self::assertInstanceOf(PlayerStatResponse::class, $client->getPlayerStats(123, 1));

        ClientResponseStub::set($client, ['data' => []]);
        self::assertInstanceOf(GoalieStatResponse::class, $client->getGoalieStats(123));

        ClientResponseStub::set($client, [
            'shotEfficiency' => 0.0,
            'ppEfficiency' => 0,
            'shEfficiency' => 0,
            'ppCount' => 0,
            'shCount' => 0,
            'goals' => 0,
            'shotsAttempts' => 0,
            'shotsOnGoal' => 0,
            'shotsMissed' => 0,
            'shotsBlocked' => 0,
            'saves' => 0,
            'savesPercent' => 0.0,
            'ppGoals' => 0,
            'shGoals' => 0,
            'ppLosses' => 0,
            'penaltyMinutes' => 0,
            'faceOffsWon' => 0,
            'faceOffsWonPercent' => 0.0,
            'faceOffsTotal' => 0,
            'hits' => 0,
        ]);
        self::assertInstanceOf(TeamStatResponse::class, $client->getTeamStats(123, 1));
    }

    public function testGameEndpointsWrapHttpFailures(): void
    {
        ClientResponseStub::inTemporaryDirectory(function (): void {
            $requests = [
                static fn(Game $client) => $client->getGameAndRoster(123),
                static fn(Game $client) => $client->getGameEvents(123),
                static fn(Game $client) => $client->getPlayerStats(123, 1),
                static fn(Game $client) => $client->getGoalieStats(123),
                static fn(Game $client) => $client->getTeamStats(123, 1),
            ];

            foreach ($requests as $request) {
                $client = new Game();
                ClientResponseStub::set($client, [], 404);

                try {
                    $request($client);
                    self::fail('Expected the HTTP failure to be rethrown.');
                } catch (Exception $exception) {
                    self::assertNotInstanceOf(RequestException::class, $exception);
                    self::assertNotSame('', $exception->getMessage());
                }
            }
        });
    }

    public function testGameEndpointsLogAndRethrowNonHttpFailures(): void
    {
        ClientResponseStub::inTemporaryDirectory(function (): void {
            $requests = [
                static fn(Game $client) => $client->getGameAndRoster(123),
                static fn(Game $client) => $client->getGameEvents(123),
                static fn(Game $client) => $client->getPlayerStats(123, 1),
                static fn(Game $client) => $client->getGoalieStats(123),
                static fn(Game $client) => $client->getTeamStats(123, 1),
            ];

            foreach ($requests as $request) {
                $client = new Game();
                ClientResponseStub::failWithConnectionError($client);

                try {
                    $request($client);
                    self::fail('Expected a connection failure.');
                } catch (ConnectException $exception) {
                    self::assertSame('Connection refused', $exception->getMessage());
                }
            }
        });
    }
}
