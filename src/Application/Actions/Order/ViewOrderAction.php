<?php

declare(strict_types=1);

namespace App\Application\Actions\Order;

use App\Domain\Order\OrderNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;


class ViewOrderAction extends OrderAction
{
    /**
     * @inheritDoc
     */

    protected function action(): Response
    {
        $orderId = (int) $this->resolveArg('id');
        $order = $this->orderRepository->findById($orderId);
        if(!$order) {
            throw new OrderNotFoundException();
        }
        return $this->respondWithData($order);
    }
}
