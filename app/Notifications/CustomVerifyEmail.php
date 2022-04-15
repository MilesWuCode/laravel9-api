<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;

class CustomVerifyEmail extends VerifyEmail
{
    protected function verificationUrl($notifiable)
    {
        $verify = $notifiable->verifies()->create();

        $query = http_build_query([
            'code' => $verify->code,
        ]);

        return config('frontend.verify_email_url') . '?' . $query;
    }
}
