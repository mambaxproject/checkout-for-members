<?php

namespace App\Observers;

use App\Enums\SituationAffiliateEnum;
use App\Jobs\GlobalPay\CreateSellerJob;
use App\Models\User;

class UserObserver
{

    public function created(User $user): void
    {
        if ($affiliateFromRegister = session('affiliate')) {
            $affiliateFromRegister->update([
                'user_id'   => $user->id,
                'situation' => SituationAffiliateEnum::ACTIVE
            ]);
        }

        session()->forget('affiliate');
    }

    public function updated(User $user): void
    {
        \Cache::forget('user::' . $user->id);
    }

}
