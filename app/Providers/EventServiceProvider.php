<?php

namespace App\Providers;

use App\Events\{AbandonedCartCreated,
    AbandonedCartNotification,
    AbandonedCartStatusChange,
    AcceptedAffiliate,
    AcceptedCoproducer,
    OrderApproved,
    OrderCanceled,
    OrderCreated,
    OrderFailed,
    OrderUpdated};
use App\Listeners\{CreateTelegramInviteLink,
    DispatchFacebookPurchasePixel,
    SendDataAbandonedCartSuitCRM,
    SendDataOrderSuitCRM,
    SendEmailAbandonedCart,
    SendEmailAcceptedAffiliate,
    SendEmailAcceptedCoproducer,
    SendEmailAccessReleasedCustomer,
    SendEmailOrderApproved,
    SendEmailOrderCreated,
    SendEmailOrderFailed,
    SendEmailOrderRecurringAproved,
    UpdateAbandonedCard};
use App\Listeners\Apps\{CreateMemberSuitMembers,
    CreateSuitpayAcademyAccount,
    DeactivateMemberSuitMembers,
    SendDataOrderActiveCampaign,
    SendDataOrderMemberKit,
    SendDataOrderUtmify,
    SendWebhooksOrderData};
use App\Listeners\Notifications\{AbandonedCartCustomNotification,
    OrderCreatedCustomNotification,
    OrderFailedCustomNotification,
    OrderPaidCustomNotification};
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        OrderCreated::class => [
            SendEmailOrderCreated::class,
            UpdateAbandonedCard::class,
            SendWebhooksOrderData::class,
            OrderCreatedCustomNotification::class,
            SendDataOrderUtmify::class,
            SendDataOrderSuitCRM::class,
        ],
        OrderApproved::class => [
            CreateTelegramInviteLink::class,
            SendEmailOrderApproved::class,
            SendEmailOrderRecurringAproved::class,
            CreateSuitpayAcademyAccount::class,
            UpdateAbandonedCard::class,
            SendEmailAccessReleasedCustomer::class,
            DispatchFacebookPurchasePixel::class,
            SendDataOrderActiveCampaign::class,
            SendWebhooksOrderData::class,
            SendDataOrderMemberKit::class,
            OrderPaidCustomNotification::class,
            SendDataOrderUtmify::class,
            CreateMemberSuitMembers::class,
            SendDataOrderSuitCRM::class,
        ],
        OrderFailed::class => [
            SendEmailOrderFailed::class,
            SendWebhooksOrderData::class,
            OrderFailedCustomNotification::class,
            SendDataOrderUtmify::class,
            DeactivateMemberSuitMembers::class,
            SendDataOrderSuitCRM::class,
        ],
        OrderUpdated::class => [
            SendWebhooksOrderData::class,
        ],
        AcceptedCoproducer::class => [
            SendEmailAcceptedCoproducer::class,
        ],
        AcceptedAffiliate::class => [
            SendEmailAcceptedAffiliate::class,
        ],
        AbandonedCartNotification::class => [
            SendEmailAbandonedCart::class,
            AbandonedCartCustomNotification::class,
            SendDataAbandonedCartSuitCRM::class,
        ],
        OrderCanceled::class => [
            DeactivateMemberSuitMembers::class,
            SendDataOrderSuitCRM::class
        ],
        AbandonedCartCreated::class => [
            //SendDataAbandonedCartSuitCRM::class
        ],
        AbandonedCartStatusChange::class => [
            SendDataAbandonedCartSuitCRM::class
        ],
    ];

    public function boot(): void
    {
        //
    }

    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
