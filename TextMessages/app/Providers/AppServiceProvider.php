<?php

namespace App\Providers;

use App\Factories\SendMessageFactory;
use App\Services\Clients\MessageBirdClient;
use App\Services\Clients\TextMessageClientInterface;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use MessageBird\Client;

class AppServiceProvider extends ServiceProvider
{
    public array $bindings = [
        TextMessageClientInterface::class => MessageBirdClient::class,
    ];

    public function register(): void
    {
        $this->app->bind(MessageBirdClient::class, function (Application $app) {
            return new MessageBirdClient(
                $app->make(SendMessageFactory::class),
                new Client(config('messagebird.access_key'))
            );
        });

        $this->app->bind(SendMessageFactory::class, function () {
            return new SendMessageFactory(config('messagebird.channel_id'));
        });
    }
}
