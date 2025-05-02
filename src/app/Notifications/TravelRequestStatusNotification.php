<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class TravelRequestStatusNotification extends Notification
{
    public function __construct(public $travel) {}

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {

        \Log::info('Salvando notificação no banco', [
            'user_id' => $notifiable->id,
            'travel' => $this->travel
        ]);

        return [
            'title' => 'Status do pedido atualizado',
            'message' => "Sua viagem para {$this->travel->destination} foi {$this->travel->status}",
            'travel_id' => $this->travel->id,
            'status' => $this->travel->status,
        ];
    }
}
