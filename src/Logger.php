<?php

namespace ChampionsLeagueClient;

use DateTimeZone;
use Monolog\Level;
use InvalidArgumentException;
use Psr\Clock\ClockInterface;
use Monolog\Logger as Monolog;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\FirePHPHandler;

final class Logger
{
    public Monolog $logger;

    public function __construct(string $name = 'champions-hockey-league-sdk', array $handlers = [], array $processors = [], ?DateTimeZone $timezone = null, ?ClockInterface $clock = null)
    {
        $level = self::isVerboseLoggingEnabled() ? Level::Debug : Level::Error;
        $logPath = '.' . DIRECTORY_SEPARATOR . $name . '.log';
        $handlers = array_merge($handlers, [
            new StreamHandler($logPath, $level),
            new FirePHPHandler($level),
        ]);

        $this->logger = new Monolog($name, $handlers, $processors, $timezone, $clock);
    }

    private static function isVerboseLoggingEnabled(): bool
    {
        $value = $_ENV['CHAMPIONS_HOCKEY_LEAGUE_VERBOSE_LOGGING']
            ?? $_SERVER['CHAMPIONS_HOCKEY_LEAGUE_VERBOSE_LOGGING']
            ?? getenv('CHAMPIONS_HOCKEY_LEAGUE_VERBOSE_LOGGING');

        if ($value === false || $value === '') {
            return false;
        }

        if (is_bool($value)) {
            return $value;
        }

        if (!is_string($value) && !is_int($value)) {
            throw new InvalidArgumentException('CHAMPIONS_HOCKEY_LEAGUE_VERBOSE_LOGGING must be a boolean value.');
        }

        return match (strtolower(trim((string) $value))) {
            '1', 'true', 'yes', 'on' => true,
            '0', 'false', 'no', 'off' => false,
            default => throw new InvalidArgumentException('CHAMPIONS_HOCKEY_LEAGUE_VERBOSE_LOGGING must be a boolean value.'),
        };
    }

    public function addDebug(string $message, array $context = [])
    {
        $this->logger->debug($message, $context);
    }

    public function addInfo(string $message, array $context = [])
    {
        $this->logger->info($message, $context);
    }

    public function addNotice(string $message, array $context = [])
    {
        $this->logger->notice($message, $context);
    }

    public function addWarning(string $message, array $context = [])
    {
        $this->logger->warning($message, $context);
    }

    public function addError(string $message, array $context = [])
    {
        $this->logger->error($message, $context);
    }

    public function addCritical(string $message, array $context = [])
    {
        $this->logger->critical($message, $context);
    }

    public function addAlert(string $message, array $context = [])
    {
        $this->logger->alert($message, $context);
    }

    public function addEmergency(string $message, array $context = [])
    {
        $this->logger->emergency($message, $context);
    }
}
