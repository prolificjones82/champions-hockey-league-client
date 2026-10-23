<?php
$envFile = dirname(__DIR__) . '/.env';

if (is_file($envFile)) {
    (new Symfony\Component\Dotenv\Dotenv())
        ->usePutenv()
        ->bootEnv($envFile);
}

require_once __DIR__ . '/ClientResponseStub.php';
