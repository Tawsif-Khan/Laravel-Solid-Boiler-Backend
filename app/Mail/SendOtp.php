<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendOtp extends Mailable /*implements ShouldQueue*/
{
    use SerializesModels;
    // use Queueable;

    /**
     * Create a new message instance.
     */
    public function __construct(public string $otp)
    {
        // $this->connection = 'database';
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
            subject: 'One Time Password for '.config('app.name').'.',
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

      <p>Dear Verifier,</p>

      <p>Your One-Time Password (OTP) for account verification:</p>

      <p>OTP: <strong>{$this->otp}</strong></p>

      <p>Please use this OTP to complete the verification process within 5 minutes. Do not share this OTP with anyone for security reasons.</p>

      <p>Thank you.</p>

    </body>

    </html>
    EOD;

        return new Content(
            htmlString: $body
        );
    }
}
