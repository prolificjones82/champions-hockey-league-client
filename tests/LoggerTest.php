<?php

declare(strict_types=1);

use Monolog\Level;
use PHPUnit\Framework\TestCase;
use Monolog\Handler\TestHandler;
use ChampionsLeagueClient\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\FirePHPHandler;
use Monolog\Handler\AbstractHandler;
use PHPUnit\Framework\Attributes\DataProvider;

final class LoggerTest extends TestCase
{
    private const ENV_KEY = 'CHAMPIONS_HOCKEY_LEAGUE_VERBOSE_LOGGING';

    private string $originalCwd;
    private string $tempDir;

    /**
     * @param callable(): void $callback
     */
    private function withVerboseEnv(mixed $value, callable $callback): void
    {
        $key = self::ENV_KEY;
        $hadEnv = array_key_exists($key, $_ENV);
        $previousEnv = $_ENV[$key] ?? null;
        $hadServer = array_key_exists($key, $_SERVER);
        $previousServer = $_SERVER[$key] ?? null;
        $previousProcessValue = getenv($key);

        try {
            if ($value === null) {
                unset($_ENV[$key], $_SERVER[$key]);
                putenv($key);
            } else {
                $_ENV[$key] = $value;
                $_SERVER[$key] = $value;
                if (is_string($value) || is_int($value) || is_bool($value)) {
                    putenv($key . '=' . (string) $value);
                } else {
                    putenv($key);
                }
            }

            $callback();
        } finally {
            if ($hadEnv) {
                $_ENV[$key] = $previousEnv;
            } else {
                unset($_ENV[$key]);
            }

            if ($hadServer) {
                $_SERVER[$key] = $previousServer;
            } else {
                unset($_SERVER[$key]);
            }

            if ($previousProcessValue === false) {
                putenv($key);
            } else {
                putenv($key . '=' . $previousProcessValue);
            }
        }
    }

    private function assertDefaultHandlerLevel(mixed $value, Level $expectedLevel): void
    {
        $this->withVerboseEnv($value, function () use ($expectedLevel): void {
            $logger = new Logger('elite_league_logger_test');
            $handlers = $logger->logger->getHandlers();

            self::assertCount(2, $handlers);
            foreach ($handlers as $handler) {
                self::assertInstanceOf(AbstractHandler::class, $handler);
                self::assertSame($expectedLevel, $handler->getLevel());
            }
        });
    }

    public function testNonErrorLogsAreDisabledByDefault(): void
    {
        $this->assertDefaultHandlerLevel(null, Level::Error);
    }

    public function testEmptyValueDisablesVerboseLogging(): void
    {
        $this->assertDefaultHandlerLevel('', Level::Error);
    }

    /**
     * @return array<string, array{string|bool|int}>
     */
    public static function truthyValues(): array
    {
        return [
            '1' => ['1'],
            'true' => ['true'],
            'TRUE' => ['TRUE'],
            'yes' => ['yes'],
            'on' => ['on'],
            'padded' => [' true '],
            'boolean true' => [true],
            'integer one' => [1],
        ];
    }

    /**
     * @return array<string, array{string|bool|int}>
     */
    public static function falsyValues(): array
    {
        return [
            '0' => ['0'],
            'false' => ['false'],
            'FALSE' => ['FALSE'],
            'no' => ['no'],
            'off' => ['off'],
            'boolean false' => [false],
            'integer zero' => [0],
        ];
    }

    #[DataProvider('truthyValues')]
    public function testTruthyValuesEnableVerboseLogging(mixed $value): void
    {
        $this->assertDefaultHandlerLevel($value, Level::Debug);
    }

    #[DataProvider('falsyValues')]
    public function testFalsyValuesDisableVerboseLogging(mixed $value): void
    {
        $this->assertDefaultHandlerLevel($value, Level::Error);
    }

    public function testInvalidValueThrows(): void
    {
        $this->withVerboseEnv('maybe', function (): void {
            $this->expectException(InvalidArgumentException::class);
            $this->expectExceptionMessage('CHAMPIONS_HOCKEY_LEAGUE_VERBOSE_LOGGING must be a boolean value.');

            new Logger('elite_league_logger_test');
        });
    }

    public function testNonScalarValueThrows(): void
    {
        $this->withVerboseEnv([], function (): void {
            $this->expectException(InvalidArgumentException::class);
            $this->expectExceptionMessage('CHAMPIONS_HOCKEY_LEAGUE_VERBOSE_LOGGING must be a boolean value.');

            new Logger('elite_league_logger_test');
        });
    }

    public function testDefaultHandlersAreStreamAndFirePhp(): void
    {
        $this->withVerboseEnv(null, function (): void {
            $handlers = (new Logger('elite_league_logger_test'))->logger->getHandlers();

            self::assertInstanceOf(StreamHandler::class, $handlers[0]);
            self::assertInstanceOf(FirePHPHandler::class, $handlers[1]);
        });
    }

    public function testCustomHandlersAreRetainedAndNameIsUsed(): void
    {
        $this->withVerboseEnv(null, function (): void {
            $custom = new TestHandler();
            $logger = new Logger('custom_name', [$custom]);

            self::assertSame('custom_name', $logger->logger->getName());
            self::assertContains($custom, $logger->logger->getHandlers());
            self::assertCount(3, $logger->logger->getHandlers());
        });
    }

    public function testLogFileIsCreatedFromNameWhenErrorIsLogged(): void
    {
        $this->withVerboseEnv(null, function (): void {
            $logger = new Logger('file_test');
            $logger->addError('boom');
            $logger->logger->close();

            $path = $this->tempDir . DIRECTORY_SEPARATOR . 'file_test.log';
            self::assertFileExists($path);
            self::assertStringContainsString('file_test.ERROR: boom', (string) file_get_contents($path));
        });
    }

    public function testLowLevelLogsAreNotWrittenToFileByDefault(): void
    {
        $this->withVerboseEnv(null, function (): void {
            $logger = new Logger('quiet_test');
            $logger->addInfo('ignored');
            $logger->logger->close();

            self::assertFileDoesNotExist($this->tempDir . DIRECTORY_SEPARATOR . 'quiet_test.log');
        });
    }

    /**
     * @return array<string, array{string, Level}>
     */
    public static function logMethods(): array
    {
        return [
            'debug' => ['addDebug', Level::Debug],
            'info' => ['addInfo', Level::Info],
            'notice' => ['addNotice', Level::Notice],
            'warning' => ['addWarning', Level::Warning],
            'error' => ['addError', Level::Error],
            'critical' => ['addCritical', Level::Critical],
            'alert' => ['addAlert', Level::Alert],
            'emergency' => ['addEmergency', Level::Emergency],
        ];
    }

    #[DataProvider('logMethods')]
    public function testAddMethodsLogMessageAndContextAtCorrectLevel(string $method, Level $level): void
    {
        $this->withVerboseEnv('true', function () use ($method, $level): void {
            $handler = new TestHandler();
            $logger = new Logger('level_test', [$handler]);

            $logger->{$method}('hello', ['foo' => 'bar']);

            self::assertCount(1, $handler->getRecords());
            $record = $handler->getRecords()[0];
            self::assertSame('hello', $record->message);
            self::assertSame($level, $record->level);
            self::assertSame(['foo' => 'bar'], $record->context);
        });
    }

    protected function setUp(): void
    {
        // The logger writes to ./<name>.log, so run inside a throwaway directory.
        $this->originalCwd = getcwd();
        $this->tempDir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'elite-league-logger-' . bin2hex(random_bytes(4));
        mkdir($this->tempDir);
        chdir($this->tempDir);
    }

    protected function tearDown(): void
    {
        chdir($this->originalCwd);
        foreach (glob($this->tempDir . DIRECTORY_SEPARATOR . '*') ?: [] as $file) {
            unlink($file);
        }
        rmdir($this->tempDir);
    }
}
