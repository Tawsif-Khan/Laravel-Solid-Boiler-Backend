<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendCertificate extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        private string $student,
        private string $course,
        private string $file
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
            subject: 'Certificate from '.config('app.name').'.',
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

      <p>Dear {$this->student},</p>

      <p>Congratulation on your successful completion if the course: <b>{$this->course}.</b></p>

      <p>Please find your course completion certificate (certificate.pdf) attached.</p>

      <p>Best wishes for you future endeavors.</p>

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
                ->as('certificate.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
