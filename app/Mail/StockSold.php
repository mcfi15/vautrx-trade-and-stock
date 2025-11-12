<?php

namespace App\Mail;

use App\Models\StockTransaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StockSold extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public StockTransaction $transaction,
        public float $profitLoss = 0
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Stock Sale Confirmed - ' . $this->transaction->stock->symbol . ' ($' . number_format($this->transaction->total_amount, 2) . ')',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.trading.stock-sold',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
