<?php

namespace App\Mail;

use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminTradeNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public Transaction $transaction
    ) {}

    public function envelope(): Envelope
    {
        $action = ucfirst($this->transaction->type);
        return new Envelope(
            subject: "Large Trade Alert - {$action} {$this->transaction->stock->symbol} (${$this->transaction->total_amount})",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin.trade-notification',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
