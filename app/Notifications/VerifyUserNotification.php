<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VerifyUserNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private ?User $user = null;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(trans('global.verifyUserRegister'))
            ->line(trans('global.verifyYourUser'))
            ->action(trans('global.clickHereToVerify'), route('userVerification', $this->user->verification_token))
            ->line(trans('global.thankYouForUsingOurApplication'));
    }

    public function toArray($notifiable): array
    {
        return [];
    }
}
