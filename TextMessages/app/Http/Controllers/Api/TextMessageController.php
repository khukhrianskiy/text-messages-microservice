<?php

namespace App\Http\Controllers\Api;

use App\Factories\OrderConfirmationMessageDtoFactory;
use App\Http\Controllers\Controller;
use App\Http\Requests\LatestTextMessages;
use App\Http\Requests\SendOrderConfirmationMessage;
use App\Jobs\SendDeliveredTextMessage;
use App\Models\TextMessage;
use App\Repositories\TextMessageRepositoryInterface;
use App\Services\TextMessagePersister;
use App\Services\TextMessageSender;
use Symfony\Component\HttpFoundation\Response;

class TextMessageController extends Controller
{
    private TextMessageSender $textMessageSender;

    private TextMessagePersister $textMessagePersister;

    private TextMessageRepositoryInterface $textMessageRepository;

    private OrderConfirmationMessageDtoFactory $orderConfirmationMessageDtoFactory;

    public function __construct(
        TextMessageSender $textMessageSender,
        TextMessagePersister $textMessagePersister,
        TextMessageRepositoryInterface $textMessageRepository,
        OrderConfirmationMessageDtoFactory $orderConfirmationMessageDtoFactory
    ) {
        $this->textMessageSender                  = $textMessageSender;
        $this->textMessagePersister               = $textMessagePersister;
        $this->textMessageRepository              = $textMessageRepository;
        $this->orderConfirmationMessageDtoFactory = $orderConfirmationMessageDtoFactory;
    }

    public function sendOrderConfirmation(SendOrderConfirmationMessage $request): Response
    {
        $textMessageDto = $this->orderConfirmationMessageDtoFactory->create($request->all());

        $status = $this->textMessageSender->send($textMessageDto);

        $textMessageDto->setStatus($status);

        $this->textMessagePersister->saveFromDto($textMessageDto);

        SendDeliveredTextMessage::dispatch($textMessageDto->getPhoneNumber())
            ->delay(now()->addMinutes(TextMessage::DELIVERED_MESSAGE_DELAY_MINUTES));

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
