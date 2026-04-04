<?php

namespace Clearfacts\Tests;

use Clearfacts\ClearfactsServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            ClearfactsServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app): array
    {
        return [
            'Clearfacts' => \Clearfacts\Facades\Clearfacts::class,
        ];
    }
}
