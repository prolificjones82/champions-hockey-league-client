<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use ChampionsLeagueClient\Game\Enums\EventType;
use ChampionsLeagueClient\Response\TeamResponse;
use ChampionsLeagueClient\Schedule\Enums\League;
use ChampionsLeagueClient\Game\Enums\PenaltyType;
use ChampionsLeagueClient\Response\ScoreResponse;
use ChampionsLeagueClient\Response\ResultResponse;
use ChampionsLeagueClient\Schedule\Enums\GameStatus;
use ChampionsLeagueClient\Response\ScoreTeamResponse;
use ChampionsLeagueClient\Game\Response\Data\GoalData;
use ChampionsLeagueClient\Game\Response\Data\TimeData;
use ChampionsLeagueClient\Interfaces\ReadonlyInterface;
use ChampionsLeagueClient\Game\Response\Data\PenaltyData;
use ChampionsLeagueClient\Game\Response\GameInfoResponse;
use ChampionsLeagueClient\Game\Response\TeamStatResponse;
use ChampionsLeagueClient\Game\Response\Data\ShootoutData;
use ChampionsLeagueClient\Game\Response\GameEventResponse;
use ChampionsLeagueClient\Game\Response\GoalieStatResponse;
use ChampionsLeagueClient\Game\Response\PlayerStatResponse;
use ChampionsLeagueClient\Game\Response\Data\GoalieStatData;
use ChampionsLeagueClient\Game\Response\Data\PlayerStatData;
use ChampionsLeagueClient\Game\Response\GameEvents\GoalEvent;
use ChampionsLeagueClient\Schedule\Response\ScheduleResponse;
use ChampionsLeagueClient\Game\Response\Data\GoalAttendantData;
use ChampionsLeagueClient\Standings\Response\StandingsResponse;
use ChampionsLeagueClient\Game\Response\GameEvents\PenaltyEvent;
use ChampionsLeagueClient\Game\Response\GameInfo\PlayerResponse;
use ChampionsLeagueClient\Game\Response\GameInfo\RosterResponse;
use ChampionsLeagueClient\Game\Response\Data\PlayerStatPointData;
use ChampionsLeagueClient\Game\Response\GameEvents\ShootoutEvent;
use ChampionsLeagueClient\Game\Response\GameInfo\BilanceResponse;
use ChampionsLeagueClient\Game\Response\GameInfo\RefereeResponse;
use ChampionsLeagueClient\Game\Response\GameInfo\TrainerResponse;
use ChampionsLeagueClient\Schedule\Response\ScheduleGameResponse;
use ChampionsLeagueClient\Game\Response\Data\GoalkeeperChangeData;
use ChampionsLeagueClient\Game\Response\GameInfo\RosterPlayerResponse;
use ChampionsLeagueClient\Game\Response\GameEvents\GoalkeeperChangeEvent;
use ChampionsLeagueClient\Game\Response\GameInfo\TeamResponse as GameTeamResponse;

final class ResponseMappingTest extends TestCase
{
    private static function rosterPlayer(): array
    {
        return [
            'playerId' => 1,
            'matchPlayerId' => 11,
            'name' => 'Alex',
            'surname' => 'Example',
            'jersey' => 9,
            'position' => 'F',
            'roster' => 'A',
        ];
    }

    private static function score(): array
    {
        $period = ['score_home' => 2, 'score_guest' => 1];

        return [
            'final' => $period,
            'first_period' => $period,
            'second_period' => $period,
            'third_period' => $period,
            'overtime' => $period,
            'shootout' => $period,
        ];
    }

    private static function resultData(): array
    {
        return [
            'extra_time' => true,
            'shooting' => false,
            'contumacy' => false,
            'score' => self::score(),
        ];
    }

    private static function gameInfo(bool $wrapped = false): array
    {
        $info = [
            'actualTimeName' => 'Final',
            'actualTimeAlias' => 'FIN',
            'stadium' => 'Arena',
            'numberOfViewers' => 1000,
            'referees' => [
                'headReferee1' => 'Ref 1',
                'headReferee2' => 'Ref 2',
                'lineReferee1' => 'Line 1',
                'lineReferee2' => 'Line 2',
            ],
            'teamInfo' => [
                'home' => ['id' => 1, 'name' => 'Home', 'shortcut' => 'HOM'],
                'visitor' => ['id' => 2, 'name' => 'Visitor', 'shortcut' => 'VIS'],
            ],
            'trainers' => ['homeHeadCoach' => 'Coach H', 'visitorHeadCoach' => 'Coach V'],
            'teamBilance' => ['homeWin' => 4, 'visitorWin' => 3],
            'results' => self::resultData(),
        ];

        if ($wrapped) {
            return [
                'gameInfo' => $info,
                'roster' => ['home' => [self::rosterPlayer()], 'visitor' => [self::rosterPlayer()]],
            ];
        }

        return $info + [
            'roster' => ['home' => [self::rosterPlayer()], 'visitor' => [self::rosterPlayer()]],
        ];
    }

    private static function penalty(): array
    {
        return [
            'id' => 8,
            'team' => 'HOM',
            'createTime' => 123,
            'disciplinedPlayer' => self::rosterPlayer(),
            'replacedPlayer' => self::rosterPlayer(),
            'fouledPlayer' => self::rosterPlayer(),
            'time' => [
                'from' => ['realTime' => '2025-01-01 12:00:00', 'scoreboardTime' => 120],
                'to' => ['realTime' => '2025-01-01 12:02:00', 'scoreboardTime' => 240],
            ],
            'duration' => 120,
            'shooting' => false,
            'disciplined_functionary' => false,
            'disciplined_bm' => false,
            'codename' => PenaltyType::HOOK_TYPE->value,
        ];
    }

    private static function goal(): array
    {
        return [
            'id' => 3,
            'team' => 'HOM',
            'currentScore' => '1:0',
            'realTime' => '2025-01-01 12:03:00',
            'scoreboardTime' => 180,
            'balance' => 'Equal Strength',
            'ea' => false,
            'en' => false,
            'shooting' => false,
            'technical' => false,
            'scorer' => self::rosterPlayer(),
            'assistants' => [self::rosterPlayer()],
            'attendants' => [
                'positive' => [self::rosterPlayer()],
                'negative' => [self::rosterPlayer()],
            ],
        ];
    }

    private static function goalkeeperChange(): array
    {
        return [
            'id' => 4,
            'team' => 'HOM',
            'time' => 300,
            'player' => self::rosterPlayer(),
            'outgoingGoalkeeper' => self::rosterPlayer(),
        ];
    }

    private static function shootout(): array
    {
        return [
            'id' => 5,
            'team' => 'HOM',
            'scorer' => self::rosterPlayer(),
            'goalkeeper' => self::rosterPlayer(),
            'order' => 1,
            'scored' => true,
            'currentScore' => '1:0',
        ];
    }

    private static function playerStatistics(): array
    {
        return [
            'teamShortcut' => 'HOM',
            'games' => 10,
            'goals' => ['home' => 5, 'away' => 2],
            'assists' => ['home' => 4, 'away' => 3],
            'points' => ['home' => 9, 'away' => 5],
            'penaltyMinutes' => 12,
            'positive' => 2,
            'negative' => 1,
            'pointsPerGame' => 0.9,
            'shotEfficiency' => 0.2,
            'penaltyMinutesPerGame' => 1.2,
            'ppGoals' => 1,
            'shGoals' => 0,
            'gwGoals' => 2,
            'shotsAttempts' => 20,
            'shotsOnGoal' => ['home' => 10, 'away' => 3],
            'shotsMissed' => 4,
            'shotsBlocked' => 3,
            'blockedShotsByPlayer' => 2,
            'faceoffsWin' => 6,
            'faceoffsLosses' => 4,
            'faceoffsCount' => 10,
            'timeonice' => 1000,
            'timeoniceEQ' => 800,
            'timeonicePP' => 150,
            'timeoniceSH' => 50,
            'hits' => 8,
        ];
    }

    private static function goalieStatistics(): array
    {
        return [
            'playerId' => 1,
            'name' => 'Goalie',
            'surname' => 'Example',
            'birthdate' => '2000-01-02',
            'teamId' => 3,
            'teamName' => 'Home',
            'teamShortcut' => 'HOM',
            'jersey' => 30,
            'matches' => 10,
            'wonMatches' => 6,
            'lostMatches' => 4,
            'saves' => 300,
            'goalsAgainst' => 20,
            'savesPercent' => 94,
            'average' => 2,
            'zeros' => 1,
            'timeOnIce' => 6000,
            'secondsPerGame' => 600,
            'minutesInPlay' => 100,
            'visibleGames' => true,
            'visibleShutouts' => true,
            'visibleWins' => true,
            'starsGames' => 2,
            'starsShutouts' => 1,
            'starsWins' => 3,
            'milestoneGames' => 10,
            'milestoneShutouts' => 1,
            'milestoneWins' => 6,
        ];
    }

    private static function scheduleGame(): array
    {
        return [
            'id' => 17,
            'start_date' => '2025-01-01 12:00:00',
            'league_id' => League::CHL->value,
            'league' => 'CHL',
            'season' => 2025,
            'round' => '1',
            'group' => 'A',
            'home' => ['id' => 1, 'name' => 'Home', 'shortcut' => 'HOM'],
            'guest' => ['id' => 2, 'name' => 'Visitor', 'shortcut' => 'VIS'],
            'status' => 'LIVE',
            'overtimeCount' => 1,
            'results' => self::resultData(),
            'actual_time_name' => 'Third period',
            'actual_time_alias' => '3rd',
            'last_event_time' => 1800,
            'arena' => 'Arena',
        ];
    }

    private static function standingsTeam(): array
    {
        return [
            'id' => 1,
            'partTeamId' => 11,
            'name' => 'Home',
            'teamOriginal' => 'Home Club',
            'shortcut' => 'HOM',
            'position' => 1,
            'points' => 30,
            'games' => 12,
            'wins' => 9,
            'winsOt' => 1,
            'winsReg' => 8,
            'ties' => 0,
            'lostsOt' => 1,
            'losts' => 2,
            'score1' => 40,
            'score2' => 20,
            'goalsAway' => 18,
            'last10' => 'WWLWW',
            'onlyOTwins' => 1,
            'onlySOwins' => 0,
            'onlyOTlosts' => 1,
            'onlySOlosts' => 0,
            'qualified' => true,
            'ppg' => '2.5',
        ];
    }

    /**
     * @return iterable<string, array{class-string<ReadonlyInterface>, array}>
     */
    public static function responseFixtures(): iterable
    {
        $period = ['score_home' => 2, 'score_guest' => 1];

        yield 'team' => [TeamResponse::class, ['id' => 1, 'name' => 'Home', 'shortcut' => 'HOM']];
        yield 'score team' => [ScoreTeamResponse::class, $period];
        yield 'score with optional periods' => [ScoreResponse::class, self::score()];
        yield 'result' => [ResultResponse::class, self::resultData()];
        yield 'schedule game' => [ScheduleGameResponse::class, self::scheduleGame()];
        yield 'schedule collection' => [ScheduleResponse::class, ['matches' => [self::scheduleGame()]]];
        yield 'standings team' => [\ChampionsLeagueClient\Standings\Response\TeamResponse::class, self::standingsTeam()];
        yield 'standings response' => [StandingsResponse::class, [
            'groupId' => 1,
            'groupName' => 'Group A',
            'complete' => true,
            'clubs' => [self::standingsTeam()],
        ]];
        yield 'roster player' => [RosterPlayerResponse::class, self::rosterPlayer()];
        yield 'player' => [PlayerResponse::class, [
            'playerId' => 1,
            'matchPlayerId' => 11,
            'jersey' => 9,
            'name' => 'Alex',
            'surname' => 'Example',
        ]];
        yield 'referees' => [RefereeResponse::class, [
            'headReferee1' => 'Ref 1',
            'headReferee2' => 'Ref 2',
            'lineReferee1' => 'Line 1',
            'lineReferee2' => 'Line 2',
        ]];
        yield 'trainers' => [TrainerResponse::class, ['homeHeadCoach' => 'Coach H', 'visitorHeadCoach' => 'Coach V']];
        yield 'bilance' => [BilanceResponse::class, ['homeWin' => 4, 'visitorWin' => 3]];
        yield 'game team info' => [GameTeamResponse::class, [
            'home' => ['id' => 1, 'name' => 'Home', 'shortcut' => 'HOM'],
            'visitor' => ['id' => 2, 'name' => 'Visitor', 'shortcut' => 'VIS'],
        ]];
        yield 'roster' => [RosterResponse::class, [
            'home' => [self::rosterPlayer()],
            'visitor' => [self::rosterPlayer()],
        ]];
        yield 'game info root shape' => [GameInfoResponse::class, self::gameInfo()];
        yield 'game info wrapped shape' => [GameInfoResponse::class, self::gameInfo(true)];
        yield 'time' => [TimeData::class, ['realTime' => '2025-01-01 12:00:00', 'scoreboardTime' => 120]];
        yield 'player stat point' => [PlayerStatPointData::class, ['home' => 5, 'away' => 2]];
        yield 'player statistics' => [PlayerStatData::class, self::playerStatistics()];
        yield 'player statistics optional defaults' => [PlayerStatData::class, ['teamShortcut' => 'HOM']];
        yield 'player stats response' => [PlayerStatResponse::class, [
            'id' => 1,
            'name' => 'Alex Example',
            'firstname' => 'Alex',
            'surname' => 'Example',
            'birthdate' => '2000-01-02',
            'active' => true,
            'position' => 'F',
            'stick' => 'L',
            'weight' => 85,
            'height' => 180,
            'jersey' => 9,
            'birthCountry' => 'GB',
            'nationality' => 'British',
            'nationalityShort' => 'GBR',
            'statistics' => self::playerStatistics(),
        ]];
        yield 'goal attendant' => [GoalAttendantData::class, [
            'positive' => [self::rosterPlayer()],
            'negative' => [self::rosterPlayer()],
        ]];
        yield 'goal' => [GoalData::class, self::goal()];
        yield 'goalkeeper change' => [GoalkeeperChangeData::class, self::goalkeeperChange()];
        yield 'penalty' => [PenaltyData::class, self::penalty()];
        yield 'shootout' => [ShootoutData::class, self::shootout()];
        yield 'goalie statistics' => [GoalieStatData::class, self::goalieStatistics()];
        yield 'goalie statistics response' => [GoalieStatResponse::class, ['data' => [self::goalieStatistics()]]];
        yield 'team statistics' => [TeamStatResponse::class, [
            'shotEfficiency' => 0.2,
            'ppEfficiency' => 25,
            'shEfficiency' => 10,
            'ppCount' => 4,
            'shCount' => 2,
            'goals' => 3,
            'shotsAttempts' => 30,
            'shotsOnGoal' => 20,
            'shotsMissed' => 5,
            'shotsBlocked' => 5,
            'saves' => 17,
            'savesPercent' => 85.0,
            'ppGoals' => 1,
            'shGoals' => 0,
            'ppLosses' => 1,
            'penaltyMinutes' => 8,
            'faceOffsWon' => 20,
            'faceOffsWonPercent' => 55.0,
            'faceOffsTotal' => 36,
            'hits' => 15,
        ]];
        yield 'events' => [GameEventResponse::class, [
            '1' => [
                ['time' => 180, 'type' => 'goal', 'data' => self::goal()],
                ['time' => 240, 'type' => EventType::GOAL_EVENT->value, 'data' => self::goal()],
            ],
            'period_two' => [
                ['time' => 300, 'type' => 'goalkeeperChange', 'data' => self::goalkeeperChange()],
                ['time' => 360, 'type' => EventType::GOALKEEPER_CHANGE_EVENT->value, 'data' => self::goalkeeperChange()],
            ],
            '3' => [
                ['time' => 420, 'type' => 'penalty', 'data' => self::penalty()],
                ['time' => 480, 'type' => EventType::PENALTY_EVENT->value, 'data' => self::penalty()],
            ],
            'overtime' => [],
            'shootout' => [['time' => 600, 'type' => 'Shootout', 'data' => self::shootout()]],
            'last_event_time' => 600,
        ]];
    }

    #[DataProvider('responseFixtures')]
    public function testResponseArraysRoundTrip(string $class, array $data): void
    {
        $response = $class::fromArray($data);

        self::assertInstanceOf(ReadonlyInterface::class, $response);
        $serialized = $response->toArray();
        self::assertSame($serialized, $class::fromArray($serialized)->toArray());
    }

    public function testOptionalFieldsAndEmptyCollectionsUseDefaults(): void
    {
        $score = ScoreResponse::fromArray([
            'final' => ['score_home' => 0, 'score_guest' => 0],
            'first_period' => ['score_home' => 0, 'score_guest' => 0],
            'second_period' => ['score_home' => 0, 'score_guest' => 0],
            'third_period' => ['score_home' => 0, 'score_guest' => 0],
        ]);
        self::assertNull($score->overtime);
        self::assertNull($score->shootout);

        self::assertSame(['matches' => []], ScheduleResponse::fromArray([])->toArray());
        self::assertSame(
            ['groupId' => null, 'groupName' => null, 'complete' => false, 'clubs' => []],
            StandingsResponse::fromArray([])->toArray(),
        );
        self::assertSame(
            ['home' => [], 'visitor' => []],
            RosterResponse::fromArray([])->toArray(),
        );
        self::assertSame(
            ['home' => 0, 'away' => 0],
            PlayerStatPointData::fromArray([])->toArray(),
        );
        self::assertSame(['data' => []], GoalieStatResponse::fromArray([])->toArray());
    }

    public function testScheduleDefaultsMissingStatusAndGroup(): void
    {
        $data = self::scheduleGame();
        unset($data['status'], $data['group']);
        unset($data['overtimeCount']);

        $response = ScheduleGameResponse::fromArray($data);

        self::assertSame(GameStatus::BEFORE_MATCH, $response->status);
        self::assertNull($response->group);
        self::assertNull($response->overtimeCount);
        self::assertSame(GameStatus::BEFORE_MATCH->value, $response->toArray()['status']);
    }

    public function testScheduleMapsApiStatusAliases(): void
    {
        $data = self::scheduleGame();
        $data['status'] = 'INTERRUPTED';

        $response = ScheduleGameResponse::fromArray($data);

        self::assertSame(GameStatus::INTERUPTED, $response->status);
        self::assertSame(GameStatus::INTERUPTED->value, $response->toArray()['status']);
    }

    public function testEventTypeAliasesAndUnknownValues(): void
    {
        self::assertSame(EventType::GOAL_EVENT, EventType::fromApiValue('goal'));
        self::assertSame(EventType::PENALTY_EVENT, EventType::fromApiValue('penalty'));
        self::assertSame(EventType::GOALKEEPER_CHANGE_EVENT, EventType::fromApiValue('goalkeeperChange'));
        self::assertSame(EventType::SHOOTOUT_EVENT, EventType::fromApiValue('shootout'));
        self::assertSame(EventType::GOAL_EVENT, EventType::fromApiValue('Goal'));

        $this->expectException(ValueError::class);
        EventType::fromApiValue('invalid');
    }

    public function testGameStatusAliasesAndUnknownValues(): void
    {
        self::assertSame(GameStatus::BEFORE_MATCH, GameStatus::fromApiValue('BEFORE_MATCH'));
        self::assertSame(GameStatus::LIVE, GameStatus::fromApiValue('LIVE'));
        self::assertSame(GameStatus::AFTER_MATCH, GameStatus::fromApiValue('AFTER_MATCH'));
        self::assertSame(GameStatus::POSTPONED, GameStatus::fromApiValue('POSTPONED'));
        self::assertSame(GameStatus::INTERUPTED, GameStatus::fromApiValue('INTERUPTED'));
        self::assertSame(GameStatus::INTERUPTED, GameStatus::fromApiValue('INTERRUPTED'));
        self::assertSame(GameStatus::CANCELLED, GameStatus::fromApiValue('CANCELLED'));
        self::assertSame(GameStatus::CANCELLED, GameStatus::fromApiValue('CANCELED'));
        self::assertSame(GameStatus::AFTER_MATCH, GameStatus::fromApiValue('Result'));

        $this->expectException(ValueError::class);
        GameStatus::fromApiValue('invalid');
    }

    public function testUnknownPeriodEventTypeIsRejected(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Unknown event type');

        GameEventResponse::fromArray([
            'period_one' => [['type' => 'unknown']],
            'last_event_time' => 0,
        ]);
    }

    public function testEventDiscriminatorsCreateTheirSpecificEventTypes(): void
    {
        $events = GameEventResponse::fromArray([
            'period_one' => [['time' => 1, 'type' => 'goal', 'data' => self::goal()]],
            'period_two' => [['time' => 2, 'type' => 'goalkeeperChange', 'data' => self::goalkeeperChange()]],
            'period_three' => [['time' => 3, 'type' => 'penalty', 'data' => self::penalty()]],
            'shootout' => [['time' => 4, 'type' => 'Shootout', 'data' => self::shootout()]],
            'last_event_time' => 4,
        ]);

        self::assertInstanceOf(GoalEvent::class, $events->period_one[0]);
        self::assertInstanceOf(GoalkeeperChangeEvent::class, $events->period_two[0]);
        self::assertInstanceOf(PenaltyEvent::class, $events->period_three[0]);
        self::assertInstanceOf(ShootoutEvent::class, $events->shootout[0]);
    }
}
