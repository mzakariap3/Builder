<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentStatusNotification extends Notification
{
    use Queueable;

    protected $status;
    protected $amount;
    protected $message;

    /**
     * Create a new notification instance.
     */
    public function __construct($status, $amount, $message = '')
    {
        $this->status = $status;
        $this->amount = $amount;
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'status' => $this->status,
            'amount' => $this->amount,
            'message' => $this->message,
            'url' => route('transactions.index'),
        ];
    }
}
