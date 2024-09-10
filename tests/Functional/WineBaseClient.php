<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use Hautelook\AliceBundle\PhpUnit\RecreateDatabaseTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Dotenv\Dotenv;

class WineBaseClient extends WebTestCase
{
    use RecreateDatabaseTrait;
    protected static ?KernelBrowser $wineBaseClient = null;

    public function setUp(): void
    {
        parent::setUp();

        (new Dotenv())->usePutenv(true)->loadEnv(dirname(__DIR__) . '/../.env.local');

        if (is_null(self::$wineBaseClient)) {
            self::$wineBaseClient = static::createClient();
            self::$wineBaseClient->setServerParameters([
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json',
            ]);
        }
    }
}
