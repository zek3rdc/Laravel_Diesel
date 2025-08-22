<?php

namespace App\Mail;

use App\Models\Venta;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Attachment;

class VentaExitosa extends Mailable
{
    use SerializesModels;

    /**
     * The venta instance.
     *
     * @var \App\Models\Venta
     */
    public $venta;

    /**
     * The path to the PDF attachment.
     *
     * @var string|null
     */
    public $pdfPath;

    /**
     * Create a new message instance.
     */
    public function __construct(Venta $venta, ?string $pdfPath = null)
    {
        $this->venta = $venta;
        $this->pdfPath = $pdfPath;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Confirmación de Compra - Factura #' . $this->venta->nro_factura,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.venta_exitosa',
            with: [
                'venta' => $this->venta,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        if ($this->pdfPath && file_exists($this->pdfPath)) {
            return [
                Attachment::fromPath($this->pdfPath)
                    ->as('factura-' . $this->venta->id . '.pdf')
                    ->withMime('application/pdf'),
            ];
        }

        return [];
    }
}
