<?php

namespace Clearfacts\Facades;

use Clearfacts\Api\ClearfactsApiClient;
use Clearfacts\ClearfactsManager;
use Illuminate\Support\Facades\Facade;

/**
 * @method static ClearfactsManager setAccessToken(string $accessToken)
 * @method static ClearfactsApiClient api()
 * @method static string|null getAccessToken()
 *
 * @see \Clearfacts\ClearfactsManager
 */
class Clearfacts extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return ClearfactsManager::class;
    }
}
