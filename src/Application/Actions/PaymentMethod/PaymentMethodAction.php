<?php

declare(strict_types=1);

namespace App\Application\Actions\PaymentMethod;

use App\Application\Actions\Action;
use App\Domain\PaymentMethod\PaymentMethodRepository;
use Psr\Log\LoggerInterface;

abstract class PaymentMethodAction extends Action
{
    protected PaymentMethodRepository $paymentMethodRepository;

    public function __construct(LoggerInterface $logger, PaymentMethodRepository $paymentMethodRepository)
    {
        parent::__construct($logger);
        $this->paymentMethodRepository = $paymentMethodRepository;
    }
}
