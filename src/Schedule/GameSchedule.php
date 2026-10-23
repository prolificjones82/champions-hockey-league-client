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
     * Get League Schedule
     *
     * @param League     $league
     * @param int|string $year
     *
     * @return ScheduleResponse
     * @throws Exception
     */
    public function getLeagueSchedule(League $league, int|string $year): ScheduleResponse
    {
        $path = sprintf('/league-matches/%s/%s.json', $year, $league->value);

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

    /**
     * Get Team Schedule
     *
     * @param League     $league
     * @param int|string $team
     * @param int|string $year
     *
     * @return ScheduleResponse
     * @throws Exception
     */
    public function getTeamSchedule(League $league, int|string $team, int|string $year): ScheduleResponse
    {
        $path = sprintf('/league-team-matches/%s/%s/%s.json', $year, $league->value, (string) $team);

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
