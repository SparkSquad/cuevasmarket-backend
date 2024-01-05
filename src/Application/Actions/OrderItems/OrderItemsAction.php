<?php

declare(strict_types=1);

namespace App\Application\Actions\OrderItems;

use App\Application\Actions\Action;
use App\Domain\OrderItems\OrderItemsRepository;
use Psr\Log\LoggerInterface;

abstract class OrderItemsAction extends Action
{
    protected OrderItemsRepository $orderItemsRepository;

    public function __construct(LoggerInterface $logger, OrderItemsRepository $orderItemsRepository)
    {
        parent::__construct($logger);
        $this->orderItemsRepository = $orderItemsRepository;
    }
}
