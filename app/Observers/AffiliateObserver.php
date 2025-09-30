<?php

namespace App\Observers;

use App\Models\Affiliate;
use Illuminate\Support\Facades\Mail;

class AffiliateObserver
{
    public function saved(Affiliate $affiliate): void
    {

    }
}
