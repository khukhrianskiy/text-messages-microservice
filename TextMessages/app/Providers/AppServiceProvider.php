<?php

namespace App\Providers;

use App\Factories\OrderDeliveredMessageDtoFactory;
use App\Jobs\SendDeliveredTextMessage;
use App\Repositories\TextMessageRepository;
use App\Repositories\TextMessageRepositoryInterface;
use App\Services\Clients\MessageBirdClient;
use App\Services\Clients\SparkPostClient;
use App\Services\Clients\TextMessageClientInterface;
use App\Services\TextMessagePersister;
use App\Services\TextMessageSender;
use Http\Adapter\Guzzle7\Client as GuzzleAdapter;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use MessageBird\Client;
use SparkPost\SparkPost;

class AppServiceProvider extends ServiceProvider
{
    public array $bindings = [
        TextMessageRepositoryInterface::class => TextMessageRepository::class,
    ];

    public function register(): void
    {
        $this->app->bind(TextMessageClientInterface::class, 'App\Services\Clients\\'.config('app.sender'));

        $this->app->bind(MessageBirdClient::class, function (Application $app) {
            return new MessageBirdClient(
                new Client(config('messagebird.access_key')),
                config('messagebird.channel_id')
            );
        });

        $this->app->bind(SparkPostClient::class, function (Application $app) {
            $httpClient = new GuzzleAdapter(new \GuzzleHttp\Client());
            $sparky = new SparkPost($httpClient, ['key' => config('sparkpost.api_key')]);

            return new SparkPostClient($sparky, config('sparkpost.from'));
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
