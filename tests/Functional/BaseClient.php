<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use Hautelook\AliceBundle\PhpUnit\RecreateDatabaseTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Dotenv\Dotenv;

class BaseClient extends WebTestCase
{
    use RecreateDatabaseTrait;
    protected static ?KernelBrowser $baseClient = null;

    public function setUp(): void
    {
        parent::setUp();

        (new Dotenv())->usePutenv(true)->loadEnv(dirname(__DIR__) . '/../.env.local');

        if (is_null(self::$baseClient)) {
            self::$baseClient = static::createClient();
            self::$baseClient->setServerParameters([
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json',
            ]);
        }
    }
}
