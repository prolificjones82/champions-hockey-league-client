<?php

namespace ChampionsLeagueClient\Schedule;

use Exception;
use ChampionsLeagueClient\Client\Client;
use GuzzleHttp\Exception\RequestException;
use ChampionsLeagueClient\Schedule\Enums\League;
use ChampionsLeagueClient\Schedule\Response\ScheduleResponse;

final class GameSchedule extends Client
{
    /**
     * Get Team Schedule
     *
     * @param League     $league
     * @param int|string $year
     * @param ?string    $team
     *
     * @return ScheduleResponse
     * @throws Exception
     */
    public function getTeamSchedule(League $league, int|string $year, ?string $team): ScheduleResponse
    {
        $path = sprintf('/league-team-matches/%s/%s/%s.json', $year, $league->value, (string) $team ?? $_ENV['CHAMPIONS_HOCKEY_LEAGUE_DEFAULT_TEAM_ID']);

        try {
            $req = $this->makeRequest('GET', $path);
            $res = $this->getResponseData($req);
            $response = ScheduleResponse::fromArray($res);
            return $response;
        } catch (RequestException $re) {
            $this->getLogger()->addError(sprintf('(%s) - %s', $re->getCode(), $re->getMessage()), [
                'method' => $re->getRequest()->getMethod(),
                'headers' => $re->getRequest()->getHeaders(),
                'uri' => $re->getRequest()->getUri(),
            ]);

            throw new Exception($re->getMessage(), $re->getCode(), $re->getPrevious());
        } catch (Exception $e) {
            $this->getLogger()->addError(sprintf('(%s) - %s', $e->getCode(), $e->getMessage()));

            throw $e;
        }
    }
}
