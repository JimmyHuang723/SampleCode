<?php

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| The first thing we will do is create a new Laravel application instance
| which serves as the "glue" for all the components of Laravel, and is
| the IoC container for the system binding all of the various parts.
|
*/

$app = new Illuminate\Foundation\Application(
    realpath(__DIR__.'/../')
);

/*
|--------------------------------------------------------------------------
| Bind Important Interfaces
|--------------------------------------------------------------------------
|
| Next, we need to bind some important interfaces into the container so
| we will be able to resolve them when needed. The kernels serve the
| incoming requests to this application from both the web and CLI.
|
*/

$app->singleton(
    Illuminate\Contracts\Http\Kernel::class,
    App\Http\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

$app->configureMonologUsing(function ($monolog) {
//    $mongo = new MongoDB\Client('mongodb://localhost:27017/');
    if(env('MGDB_USERNAME')=='')
        $mongo = new MongoDB\Client(env('MGDB_CONNECTION').'://'.env('MGDB_HOST').':'.env('MGDB_PORT').'/');
    else
        $mongo = new MongoDB\Client(env('MGDB_CONNECTION').'://'.env('MGDB_USERNAME').':'.env('MGDB_PASSWORD').'@'.env('MGDB_HOST').':'.env('MGDB_PORT').'/');
//    $mongo_handler = new Monolog\Handler\MongoDBHandler($mongo, 'db_name', 'collection_name');
    $mongo_handler = new Monolog\Handler\MongoDBHandler($mongo, env('MGDB_DATABASE'), env('MGDB_LOGS'));
    $monolog->pushHandler($mongo_handler);
    $monolog->pushProcessor(function ($record) {
        $record['timestamp'] = DateTime::createFromFormat('U.u', microtime(true))->format("Y-m-d H:i:s.u");
        return $record;
    });
});

/*
|--------------------------------------------------------------------------
| Return The Application
|--------------------------------------------------------------------------
|
| This script returns the application instance. The instance is given to
| the calling script so we can separate the building of the instances
| from the actual running of the application and sending responses.
|
*/

return $app;
