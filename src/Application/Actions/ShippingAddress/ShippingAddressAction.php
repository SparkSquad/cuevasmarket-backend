<?php

declare(strict_types=1);

namespace App\Application\Actions\ShippingAddress;

use App\Application\Actions\Action;
use App\Domain\ShippingAddress\ShippingAddressRepository;
use Psr\Log\LoggerInterface;

abstract class ShippingAddressAction extends Action
{
    protected ShippingAddressRepository $shippingAddressRepository;

    public function __construct(LoggerInterface $logger, ShippingAddressRepository $shippingAddressRepository)
    {
        parent::__construct($logger);
        $this->shippingAddressRepository = $shippingAddressRepository;
    }
}
