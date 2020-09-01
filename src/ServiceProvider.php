<?php

namespace LPhilippo\CastableFormRequest;

use Illuminate\Contracts\Validation\ValidatesWhenResolved;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Lphilippo\CastableFormRequest\Http\Requests\LumenFormRequest;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->app->afterResolving(ValidatesWhenResolved::class, function ($resolved) {
            $resolved->validateResolved();
        });

        $this->app->resolving(LumenFormRequest::class, function ($request, $app) {
            $request = LumenFormRequest::createFrom($app['request'], $request);

            $request->setContainer($app);
        });
    }
}
