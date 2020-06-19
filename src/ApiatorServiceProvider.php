<?php
declare(strict_types = 1);

namespace Patrikap\Apiator;

use Illuminate\Support\ServiceProvider;
use Patrikap\Apiator\Contracts\JsonLoggerInterface;
use Patrikap\Apiator\Contracts\JsonResponseFormatterInterface;
use Patrikap\Apiator\Services\ApiatorService;
use Illuminate\Contracts\Support\DeferrableProvider;

/**
 * Class ApiatorServiceProvider
 * @package Patrikap\Apiator
 *
 * Base apiator service provider
 *
 * @author Konstantin.K
 */
class ApiatorServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /** @var string path to config file */
    private const CONFIG_PATH = __DIR__ . '/../config/';
    /** @var string name of config file */
    private const CONFIG_NAME = 'apiator.php';

    /**
     * @inheritDoc
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                self::CONFIG_PATH . self::CONFIG_NAME => config_path(self::CONFIG_NAME),
            ], 'config');
        }
    }

    /** @inheritDoc */
    public function register(): void
    {
        $this->mergeConfigFrom(self::CONFIG_PATH . self::CONFIG_NAME, 'apiator');
        $this->app->singleton(ApiatorService::class);
        $this->app->singleton(JsonResponseFormatterInterface::class, config('apiator.responseFormatter'));
        $this->app->singleton(JsonLoggerInterface::class, config('apiator.logger'));
    }

    /** @inheritDoc */
    public function provides(): array
    {
        return [ApiatorService::class, JsonResponseFormatterInterface::class];
    }
}
