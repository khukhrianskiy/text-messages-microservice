<?php

namespace App\Providers;

use App\Factories\OrderDeliveredMessageDtoFactory;
use App\Jobs\SendDeliveredTextMessage;
use App\Repositories\TextMessageRepository;
use App\Repositories\TextMessageRepositoryInterface;
use App\Services\Clients\MessageBirdClient;
use App\Services\Clients\TextMessageClientInterface;
use App\Services\TextMessagePersister;
use App\Services\TextMessageSender;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use MessageBird\Client;

class AppServiceProvider extends ServiceProvider
{
    public array $bindings = [
        TextMessageClientInterface::class     => MessageBirdClient::class,
        TextMessageRepositoryInterface::class => TextMessageRepository::class,
    ];

    public function register(): void
    {
        $this->app->bind(MessageBirdClient::class, function (Application $app) {
            return new MessageBirdClient(
                new Client(config('messagebird.access_key')),
                config('messagebird.channel_id')
            );
        });

        $this->app->bindMethod(SendDeliveredTextMessage::class.'@handle', function ($job, $app) {
            return $job->handle(
                $app->make(OrderDeliveredMessageDtoFactory::class),
                $app->make(TextMessageSender::class),
                $app->make(TextMessagePersister::class)
            );
        });
    }
}
