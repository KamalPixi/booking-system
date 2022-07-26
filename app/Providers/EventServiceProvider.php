<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

use App\Events\Deposited;
use App\Events\AirTicketIssueReq;
use App\Listeners\CreateDepositTransaction;
use App\Listeners\AirTicketIssueReqListener;
use App\Events\RegisterAgent;
use App\Listeners\RegisterAgentListener;
use App\Events\AgentDelete;
use App\Listeners\AgentDeleteListener;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        Deposited::class => [
            CreateDepositTransaction::class,
        ],
        RegisterAgent::class => [
            RegisterAgentListener::class,
        ],
        AgentDelete::class => [
            AgentDeleteListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
