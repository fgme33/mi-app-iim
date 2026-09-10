<?php

namespace App\Mail;

use App\Models\Solicitud;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SolicitudCreadaMail extends Mailable
{
    use Queueable, SerializesModels;

    public $solicitud;

    public function __construct($solicitud)
    {
        $this->solicitud = $solicitud;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Confirmación de Solicitud: ' . $this->solicitud->folio,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.solicitud_creada',
        );
    }
}
