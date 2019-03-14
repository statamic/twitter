<?php

namespace Statamic\Twitter;

use Statamic\Extend\ServiceProvider as BaseProvider;

class ServiceProvider extends BaseProvider
{
    protected $scripts = [
        __DIR__.'/../public/js/twitter.js',
    ];

    protected $fieldtypes = [
        Fieldtypes\Twitter::class,
    ];
}
