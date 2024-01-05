<?php

declare(strict_types=1);

namespace App\Application\Actions\User;

use App\Application\Actions\Action;
use App\Application\Settings\SettingsInterface;
use App\Domain\Store\StoreBranchRepository;
use App\Domain\User\UserRepository;
use Psr\Log\LoggerInterface;

abstract class UserAction extends Action
{
    protected UserRepository $userRepository;
    protected StoreBranchRepository $storeBranchRepository;

    public function __construct(LoggerInterface $logger, SettingsInterface $settings, UserRepository $userRepository, StoreBranchRepository $storeBranchRepository)
    {
        parent::__construct($logger, $settings);
        $this->userRepository = $userRepository;
        $this->storeBranchRepository = $storeBranchRepository;
    }
}
