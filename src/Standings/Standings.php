<?php

namespace ChampionsLeagueClient\Standings;

use Exception;
use ChampionsLeagueClient\Client\Client;
use GuzzleHttp\Exception\RequestException;
use ChampionsLeagueClient\Schedule\Enums\League;
use ChampionsLeagueClient\Standings\Response\StandingsResponse;

final class Standings extends Client
{
    public function getStandings(int|string $year)
    {
        $path = sprintf('/table/%s/%s.json', (string) $year, League::CHL->value);

        try {
            $req = $this->makeRequest('GET', $path);
            $res = $this->getResponseData($req);
            $response = StandingsResponse::fromArray($res);
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
