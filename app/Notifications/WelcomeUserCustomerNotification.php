<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeUserCustomerNotification extends Notification implements ShouldQueue
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
            ->subject('Bem-vindo à ' . config('app.name'))
            ->level('Oi, ' . $this->user->name . '! Bem-vindo à ' . config('app.name') . '.')
            ->line('Obrigada por se cadastrar na ' . config('app.name') . '!. Seus dados serão registrados de maneira segura em nosso sistema.')
            ->line('Para acessar sua conta, use seu e-mail e o número do seu CPF.')
            ->action('Acessar conta', route('login'));
    }

    public function toArray($notifiable): array
    {
        return [];
    }

}
