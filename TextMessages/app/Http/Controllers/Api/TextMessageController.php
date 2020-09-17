<?php

namespace App\Http\Controllers\Api;

use App\Builders\TextMessageDirector;
use App\Http\Controllers\Controller;
use App\Http\Requests\LatestTextMessages;
use App\Http\Requests\SendOrderConfirmationMessage;
use App\Repositories\TextMessageRepositoryInterface;
use App\Services\TextMessagePersister;
use App\Services\TextMessageSender;
use Symfony\Component\HttpFoundation\Response;

class TextMessageController extends Controller
{
    private TextMessageDirector $textMessageDirector;

    private TextMessageSender $textMessageSender;

    private TextMessagePersister $textMessagePersister;

    private TextMessageRepositoryInterface $textMessageRepository;

    public function __construct(
        TextMessageDirector $textMessageDirector,
        TextMessageSender $textMessageSender,
        TextMessagePersister $textMessagePersister,
        TextMessageRepositoryInterface $textMessageRepository
    ) {
        $this->textMessageDirector   = $textMessageDirector;
        $this->textMessageSender     = $textMessageSender;
        $this->textMessagePersister  = $textMessagePersister;
        $this->textMessageRepository = $textMessageRepository;
    }

    public function sendOrderConfirmation(SendOrderConfirmationMessage $request): Response
    {
        $textMessage = $this->textMessageDirector->buildOrderConfirmationMessage(
                $request->get('restaurant_name'),
                $request->get('delivery_time'),
                $request->get('phone_number')
            )->get();

        $this->textMessagePersister->save($textMessage);

        $this->textMessageSender->send($textMessage);

        return response()->json([], Response::HTTP_NO_CONTENT);
    }

    public function latest(LatestTextMessages $request): Response
    {
        $textMessages = $this->textMessageRepository->getLatest($request->get('limit'));

        return response()->json($textMessages);
    }

    public function failed(): Response
    {
        $textMessages = $this->textMessageRepository->getTodaysFailed();

        return response()->json($textMessages);
    }
}
