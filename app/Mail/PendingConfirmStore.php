<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PendingConfirmStore extends Mailable
{
    use Queueable, SerializesModels;

    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function build()
    {
        return $this->view('emails.store-pending-confirm')
            ->from('no-reply@naslojas.com', 'naslojas')
            ->to($this->data->product->store->user->first()->email)
            ->with(['confirm' => $this->data]);
    }
}
