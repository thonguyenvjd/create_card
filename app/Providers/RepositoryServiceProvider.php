<?php

namespace App\Providers;

use App\Contracts\Repositories\AssetRepositoryInterface;
use App\Contracts\Repositories\DistributionHistoryRepositoryInterface;
use App\Contracts\Repositories\DistributionDetailRepositoryInterface;
use App\Contracts\Repositories\DistributionTrackingRepositoryInterface;
use App\Contracts\Repositories\EmailSettingRepositoryInterface;
use App\Contracts\Repositories\RecipientRepositoryInterface;
use App\Contracts\Repositories\GroupRepositoryInterface;
use App\Contracts\Repositories\RecipientAssignRepositoryInterface;
use App\Contracts\Repositories\RecipientJobRepositoryInterface;
use App\Contracts\Repositories\ReservationRepositoryInterface;
use App\Contracts\Repositories\SentenceRepositoryInterface;
use App\Contracts\Repositories\TemplateRepositoryInterface;
use App\Contracts\Repositories\UserRepositoryInterface;
use App\Repositories\AssetRepository;
use App\Repositories\DistributionDetailRepository;
use App\Repositories\DistributionHistoryRepository;
use App\Repositories\DistributionTrackingRepository;
use App\Repositories\EmailSettingRepository;
use App\Repositories\RecipientRepository;
use App\Repositories\GroupRepository;
use App\Repositories\RecipientJobRepository;
use App\Repositories\ReservationRepository;
use App\Repositories\SentenceRepository;
use App\Repositories\TemplateRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(DistributionHistoryRepositoryInterface::class, DistributionHistoryRepository::class);
        $this->app->bind(DistributionDetailRepositoryInterface::class, DistributionDetailRepository::class);
        $this->app->bind(ReservationRepositoryInterface::class, ReservationRepository::class);
        $this->app->bind(AssetRepositoryInterface::class, AssetRepository::class);
        $this->app->bind(DistributionTrackingRepositoryInterface::class, DistributionTrackingRepository::class);
        $this->app->bind(EmailSettingRepositoryInterface::class, EmailSettingRepository::class);
        $this->app->bind(GroupRepositoryInterface::class, GroupRepository::class);
        $this->app->bind(RecipientRepositoryInterface::class, RecipientRepository::class);
        $this->app->bind(RecipientJobRepositoryInterface::class, RecipientJobRepository::class);
        $this->app->bind(RecipientAssignRepositoryInterface::class, RecipientAssignRepository::class);
        $this->app->bind(SentenceRepositoryInterface::class, SentenceRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(TemplateRepositoryInterface::class, TemplateRepository::class);
    }
}
