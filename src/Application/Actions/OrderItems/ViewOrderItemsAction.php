<?php

declare(strict_types=1);

namespace App\Application\Actions\OrderItems;

use App\Domain\OrderItems\OrderItemsNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;

class ViewOrderItemsAction extends OrderItemsAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $orderItemsId = (int) $this->resolveArg('id');
        $orderItems = $this->orderItemsRepository->findById($orderItemsId);
        if(!$orderItems) {
            throw new OrderItemsNotFoundException();
        }
        return $this->respondWithData($orderItems);
    }
}
