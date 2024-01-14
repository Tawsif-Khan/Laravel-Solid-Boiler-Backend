<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendReceipt extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        private string $client,
        private string $type,
        private string $file,
    ) {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(
                address: config('mail.from.address'),
                name: 'Cadd Corner - System'
            ),
            subject: 'Receipt from '.config('app.name').'.',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $body = <<<EOD
    <!DOCTYPE html>
    <html lang="en">

    <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>One-Time Password (OTP) for Secure Account Access</title>
    </head>

    <body>

      <p>Dear {$this->client},</p>

      <p>Here is the receipt for you '{$this->type}' fee.</p>

      <p>Please find the receipt file (receipt.pdf) attached for details.</p>

      <p>Thank you.</p>

    </body>

    </html>
    EOD;

        return new Content(
            htmlString: $body
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [
            Attachment::fromStorage($this->file)
                ->as('receipt.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
