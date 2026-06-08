<?php

namespace App\Support;

class SafeHtml
{
    /**
     * Allow a minimal tag set for server-built product history notifications.
     */
    public static function productNotification($html)
    {
        return strip_tags((string) $html, '<span><p><br><strong><em>');
    }
}
