<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendPassword extends Mailable
{
    use SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public string $password,
        public string $name,
        public string $email
    ) {
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
            subject: 'Credentials for '.config('app.name').'.',
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
                <title>Password (OTP) for Secure Account Access</title>
            </head>

            <body>

                <p>Dear {$this->name},</p>

                <p>Here is your Mail and Password for account login:</p>

                <p>Mail: <strong>{$this->email}</strong></p>
                <p>Password: <strong>{$this->password}</strong></p>

                <p>Please use this Mail and Password to complete login process. Do not share this with anyone for security reasons.</p>

                <p>Thank you.</p>

            </body>

            </html>
        EOD;

        return new Content(
            htmlString: $body
        );
    }
}
