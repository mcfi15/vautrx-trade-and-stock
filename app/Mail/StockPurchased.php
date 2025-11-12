<?php

namespace App\Mail;

use App\Models\StockTransaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StockPurchased extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public StockTransaction $transaction
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Stock Purchase Confirmed - ' . $this->transaction->stock->symbol . ' ($' . number_format($this->transaction->total_amount, 2) . ')',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.trading.stock-purchased',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
