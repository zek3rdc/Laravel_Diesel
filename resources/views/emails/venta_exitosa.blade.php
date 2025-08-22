<x-mail::message>
# ¡Gracias por tu compra, {{ $venta->cliente->nombre }}!

Hola {{ $venta->cliente->nombre }},

Te confirmamos que tu compra ha sido procesada con éxito. A continuación, te dejamos los detalles de tu pedido.

**Factura Nro:** {{ $venta->nro_factura }}
**Fecha:** {{ $venta->created_at->format('d/m/Y H:i') }}

<x-mail::table>
| Producto | Cantidad | Precio Unitario | Subtotal |
| :------------- |:-------------:|:----------------:| --------:|
@foreach ($venta->detalles as $detalle)
| {{ $detalle->producto->nombre }} | {{ $detalle->cantidad }} | ${{ number_format($detalle->precio_venta_unitario, 2) }} | ${{ number_format($detalle->cantidad * $detalle->precio_venta_unitario, 2) }} |
@endforeach
</x-mail::table>

**Total de la Compra: ${{ number_format($venta->total, 2) }}**

Gracias por preferirnos. ¡Esperamos verte pronto!

Saludos,<br>
El equipo de {{ config('app.name') }}
</x-mail::message>
