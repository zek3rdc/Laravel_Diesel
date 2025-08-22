<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Attachment;

class NotificacionOrdenCompra extends Mailable
{
    use Queueable, SerializesModels;

    public $proveedor;
    public $productos;
    public $mensaje;
    public $nombreEmpresa;
    public $rifEmpresa;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($proveedor, $productos, $mensaje, $nombreEmpresa, $rifEmpresa)
    {
        $this->proveedor = $proveedor;
        $this->productos = $productos;
        $this->mensaje = $mensaje;
        $this->nombreEmpresa = $nombreEmpresa;
        $this->rifEmpresa = $rifEmpresa;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Solicitud de Cotización/Pedido de Productos - ' . $this->nombreEmpresa,
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: 'emails.orden_compra',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
