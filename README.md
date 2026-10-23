# Champions Hockey League PHP SDK

A PHP client for reading Champions Hockey Ice Hockey League (CHL) schedules, standings, match information, and match statistics from the Champions Hockey League API.

The SDK sends requests to the API's public JSON endpoint and maps responses to typed, immutable PHP response objects.

## Requirements

- PHP 8.4 or later
- Composer

## Installation

Install the package with Composer:

```bash
composer require prolificjones/champions-hockey-league-client
```

Composer's autoloader uses the `ChampionsLeagueClient\\` namespace for classes in this package. Include it in a standalone script when it is not already loaded by your application:

```php
require __DIR__ . '/vendor/autoload.php';
```

## Quick start

Each API area has its own client. The methods return response objects rather than raw JSON:

```php
<?php

require __DIR__ . '/vendor/autoload.php';

use ChampionsLeagueClient\Game\Game;
use ChampionsLeagueClient\Schedule\Enums\League;
use ChampionsLeagueClient\Schedule\GameSchedule;
use ChampionsLeagueClient\Standings\Standings;

$league = League::CHL;
$season = 2025;

$schedule = (new GameSchedule())->getLeagueSchedule($league, $season);
foreach ($schedule->matches as $match) {
    printf(
        "%s: %s vs %s (%s)\n",
        $match->start_date->format('Y-m-d H:i'),
        $match->home->name,
        $match->guest->name,
        $match->status->value,
    );
}

$standings = (new Standings())->getStandings($league, $season);
foreach ($standings->clubs as $club) {
    printf("%d. %s - %d points\n", $club->position, $club->name, $club->points);
}

$game = (new Game())->getGameAndRoster(12345);
printf("%s vs %s\n", $game->teamInfo->home->name, $game->teamInfo->visitor->name);
```

Replace `2025` and `12345` with the season and match ID you want to query. Season values are passed through to the API as supplied.

## Clients and methods

All clients accept an optional array of additional HTTP headers. The SDK supplies `Cache-Control: no-cache` and `Content-Type: application/json` by default.

### Schedule

```php
use ChampionsLeagueClient\Schedule\Enums\League;
use ChampionsLeagueClient\Schedule\GameSchedule;

$client = new GameSchedule();

$leagueSchedule = $client->getLeagueSchedule(League::CHL, 2025);
$teamSchedule = $client->getTeamSchedule(League::CHL, 96, 2025);
```

| Method | Description | Response |
| --- | --- | --- |
| `getLeagueSchedule(League $league, int|string $year)` | All listed games for a league and season | `ScheduleResponse` |
| `getTeamSchedule(League $league, int|string $team, int|string $year)` | Games for a team in a league and season | `ScheduleResponse` |

`ScheduleResponse::$matches` contains `ScheduleGameResponse` objects. A match includes its ID, start date (`DateTime`), league and season, home and guest teams, status, results, venue, and game-clock metadata.

### Standings

```php
use ChampionsLeagueClient\Schedule\Enums\League;
use ChampionsLeagueClient\Standings\Standings;

$standings = (new Standings())->getStandings(League::CHL, 2025);

foreach ($standings->clubs as $club) {
    echo $club->name . ': ' . $club->points . " points\n";
}
```

`getStandings(League $league, int|string $year)` returns a `StandingsResponse`. It contains optional group metadata, a completion flag, and `clubs`, an array of team response objects with position, points, games played, results, and scoring fields.

### Match information and statistics

```php
use ChampionsLeagueClient\Game\Game;

$client = new Game();

$info = $client->getGameAndRoster(12345);
$events = $client->getGameEvents(12345);
$playerStats = $client->getPlayerStats(12345, 96);
$goalieStats = $client->getGoalieStats(12345);
$teamStats = $client->getTeamStats(12345, 96);
```

| Method | Description | Response |
| --- | --- | --- |
| `getGameAndRoster(int|string $game_id)` | Match details, score, teams, officials, and rosters | `GameInfoResponse` |
| `getGameEvents(int|string $game_id)` | Events grouped by period, overtime, and shootout | `GameEventResponse` |
| `getPlayerStats(int|string $game_id, int|string $team_id)` | Player statistics for a match and team | `PlayerStatResponse` |
| `getGoalieStats(int|string $game_id)` | Goalie statistics for a match | `GoalieStatResponse` |
| `getTeamStats(int|string $game_id, int|string $team_id)` | Team statistics for a match | `TeamStatResponse` |

The response classes expose their fields as public, typed, readonly properties. Nested values are also response objects, and collections contain mapped response objects rather than untyped JSON arrays.

## League and game status enums

Pass a `League` enum case to schedule and standings methods:

| Case | API value |
| --- | ---: |
| `League::CHL` | `3` |
| `League::CHALLENGE_CUP` | `4` |
| `League::PLAYOFFS` | `5` |

Schedule matches expose their status as a `GameStatus` enum in `$match->status`. Its values are `Fixture`, `Live`, `Result`, `Postponed`, `Interupted`, and `Cancelled`. API spellings such as `INTERRUPTED` and `CANCELED` are normalized when mapping responses.

## Working with responses

All response DTOs implement `ReadonlyInterface`, with `toArray()` for converting a response (including nested response objects) to arrays and `fromArray()` for constructing one from API-shaped data:

```php
$data = $schedule->toArray();
$firstMatch = $data['matches'][0] ?? null;
```

Date fields are represented as `DateTime` objects on the response and formatted back to API-style strings by `toArray()`.

## HTTP headers

Pass extra or overriding headers when constructing a client:

```php
$client = new Game([
    'X-Application' => 'my-hockey-app',
]);
```

These are merged with the default headers. The base API URL is configured by the SDK.

## Logging

The SDK writes logs to `./champions-hockey-league-sdk.log` (relative to the process working directory) and also configures a FirePHP handler. By default, handlers log errors and more severe messages. To enable verbose logging, set `CHAMPIONS_HOCKEY_LEAGUE_VERBOSE_LOGGING` to a boolean-like value such as `true`, `1`, `yes`, or `on`:

```bash
CHAMPIONS_HOCKEY_LEAGUE_VERBOSE_LOGGING=true php app.php
```

Verbose mode enables debug-and-higher messages, including request metadata. Since request context can include configured header values, protect the log file and avoid verbose mode where those values should not be recorded. Invalid values for `CHAMPIONS_HOCKEY_LEAGUE_VERBOSE_LOGGING` raise an `InvalidArgumentException`.

## Errors

HTTP request failures are logged and rethrown as exceptions. Connection failures and response-mapping or JSON-decoding errors are also allowed to propagate; callers should handle exceptions at an appropriate application boundary:

```php
try {
    $schedule = (new GameSchedule())->getLeagueSchedule(League::CHL, 2025);
} catch (\Throwable $exception) {
    error_log(sprintf('Unable to load the league schedule: %s', $exception->getMessage()));
    throw $exception;
}
```

## Development

Install development dependencies and run the test suite:

```bash
composer install
composer test
```

Formatting can be checked with:

```bash
composer format:check
```

## License

This package is licensed under the [GNU General Public License v3.0 or later](LICENSE).
