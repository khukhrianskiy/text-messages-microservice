<?php

namespace App\Http\Controllers\Api;

use App\Builders\TextMessageDirector;
use App\Http\Controllers\Controller;
use App\Http\Requests\SendOrderConfirmationMessage;
use App\Services\TextMessagePersister;
use App\Services\TextMessageSender;
use Symfony\Component\HttpFoundation\Response;

class OrderConfirmationMessageController extends Controller
{
    private TextMessageDirector $textMessageDirector;

    private TextMessageSender $textMessageSender;

    private TextMessagePersister $textMessagePersister;

    public function __construct(
        TextMessageDirector $textMessageDirector,
        TextMessageSender $textMessageSender,
        TextMessagePersister $textMessagePersister
    ) {
        $this->textMessageDirector  = $textMessageDirector;
        $this->textMessageSender    = $textMessageSender;
        $this->textMessagePersister = $textMessagePersister;
    }

    public function send(SendOrderConfirmationMessage $request): Response
    {
        $textMessage = $this->textMessageDirector->buildOrderConfirmationMessage(
                $request->get('restaurant_name'),
                $request->get('delivery_time'),
                $request->get('phone_number')
            )->get();

        $this->textMessagePersister->save($textMessage);

        $this->textMessageSender->send($textMessage);

        return response()->json();
    }
}
