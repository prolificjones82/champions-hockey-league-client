<?php

namespace ChampionsLeagueClient\Interfaces;

use Symfony\Component\HttpFoundation\Response;

interface ConnectionInterface
{
    public function makeRequest(string $method, string $path, ?array $data = [], ?array $headers = []): Response;
}
