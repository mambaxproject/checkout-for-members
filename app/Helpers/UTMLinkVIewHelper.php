<?php

namespace App\Helpers;

class UTMLinkVIewHelper
{
    public static function countView($product): void
    {
        $utms = request()->only(['utm_source','utm_medium','utm_campaign','utm_content','utm_term']);

        $utmLink = $product->utmLinks()
            ->where('utm_source', $utms['utm_source'] ?? null)
            ->where('utm_medium', $utms['utm_medium'] ?? null)
            ->where('utm_campaign', $utms['utm_campaign'] ?? null)
            ->where('utm_term', $utms['utm_term'] ?? null)
            ->where('utm_content', $utms['utm_content'] ?? null)
            ->first();

        if (!$utmLink) return;

        views($utmLink)->record();
    }
}
