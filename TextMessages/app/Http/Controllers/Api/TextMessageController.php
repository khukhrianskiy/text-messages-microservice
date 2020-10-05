<?php

namespace App\Http\Controllers\Api;

use App\Factories\OrderConfirmationMessageDtoFactory;
use App\Http\Controllers\Controller;
use App\Http\Requests\LatestTextMessages;
use App\Http\Requests\SendOrderConfirmationMessage;
use App\Repositories\TextMessageRepositoryInterface;
use App\Services\Dispatchers;
use App\Services\TextMessagePersister;
use App\Services\TextMessageSender;
use Symfony\Component\HttpFoundation\Response;

class TextMessageController extends Controller
{
    private TextMessageSender $textMessageSender;

    private TextMessagePersister $textMessagePersister;

    private TextMessageRepositoryInterface $textMessageRepository;

    private OrderConfirmationMessageDtoFactory $orderConfirmationMessageDtoFactory;

    private Dispatchers $dispatchers;

    public function __construct(
        TextMessageSender $textMessageSender,
        TextMessagePersister $textMessagePersister,
        TextMessageRepositoryInterface $textMessageRepository,
        OrderConfirmationMessageDtoFactory $orderConfirmationMessageDtoFactory,
        Dispatchers $dispatchers
    ) {
        $this->textMessageSender                  = $textMessageSender;
        $this->textMessagePersister               = $textMessagePersister;
        $this->textMessageRepository              = $textMessageRepository;
        $this->orderConfirmationMessageDtoFactory = $orderConfirmationMessageDtoFactory;
        $this->dispatchers                        = $dispatchers;
    }

    public function sendOrderConfirmation(SendOrderConfirmationMessage $request): Response
    {
        $textMessageDto = $this->orderConfirmationMessageDtoFactory->create($request->all());

        $status = $this->textMessageSender->send($textMessageDto);

        $textMessageDto->setStatus($status);

        $this->textMessagePersister->saveFromDto($textMessageDto);

        $this->dispatchers->afterSendDeliveredTextMessage($textMessageDto);

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
