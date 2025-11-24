
@extends('layouts.main') {{-- o el layout que uses --}}

@section('content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<div class="container mx-auto text-center p-6">

    <h1 class="text-2xl font-bold mb-4">Se confirmo tu reserva</h1>
    <div>
    </div>
        <div class="">
                   <div class="card shadow-sm w-75 mx-auto mt-4">
  <div class="card-header bg-primary text-white fw-bold">
    Detalles de la reservación
  </div>
  <div class="card-body p-0">
    <table class="table mb-0 align-middle">
      <tbody>
    <tr>
          <th class="bg-light text-end w-25">Usuario</th>
          <td>{{ $reservacion->user->name ?? 'Desconocido' }}</td>
        </tr>
        <tr>
          <th class="bg-light text-end">Cancha</th>
          <td>{{ $reservacion->cancha->nombre ?? 'Sin cancha' }}</td>
        </tr>
        <tr>
          <th class="bg-light text-end">Fecha de Reservación</th>
          <td>{{ \Carbon\Carbon::parse($reservacion->reservacion_date)->format('d/m/Y') }}</td>
        </tr>
        <tr>
          <th class="bg-light text-end">Hora de Inicio</th>
          <td>{{ \Carbon\Carbon::parse($reservacion->hora_inicio)->format('H:i') }}</td>
        </tr>
        <tr>
          <th class="bg-light text-end">Hora de Finalización</th>
          <td>{{ \Carbon\Carbon::parse($reservacion->hora_final)->format('H:i') }}</td>
        </tr>
        <tr>
          <th class="bg-light text-end">Tipo de Reserva</th>
          <td>{{ $reservacion->tipoReservacion->franja_horaria ?? 'N/A' }} hora(s)</td>
        </tr>
        <tr>
          <th class="bg-light text-end">Estado</th>
          <td>{{ ucfirst($reservacion->status) }}</td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
    <div class="d-flex justify-content-around mt-4">
        <button class="btn btn-success" onclick="descargarTicket()">
            Descargar Ticket PDF
        </button>
        <a href="{{route('home')}}" class="btn btn-primary">Volver</a>
    </div>

        </div>
    
    
</div>

{{-- Script para cambiar el día --}}
<script>
async function descargarTicket() {
    const { jsPDF } = window.jspdf;

    const doc = new jsPDF({
        orientation: "portrait",
        unit: "mm",
        format: "a5" // Recibo cuadrado tipo factura
    });

    // MARGENES
    const margin = 12;
    let y = margin;

   // ===== ENCABEZADO =====
    doc.setFillColor(40, 80, 160);
    doc.rect(0, 0, 210, 35, "F");

    doc.setFont("helvetica", "bold");
    doc.setFontSize(18);
    doc.setTextColor(255, 255, 255);
    doc.text("COMPROBANTE DE RESERVA", 12, 22); // 👈 IZQUIERDA Y SE VE PERFECTO


    // Volver a color negro
    doc.setTextColor(0, 0, 0);
    y = 40;

    // ===== INFORMACIÓN GENERAL =====
    doc.setFontSize(12);
    doc.setFont("helvetica", "bold");
    doc.text("Datos del Cliente", margin, y);
    y += 5;

    doc.setFont("helvetica", "");
    doc.setFontSize(11);

    let cliente = [
        ["Usuario:", "{{ $reservacion->user->name }}"],
        ["Club:", "{{ $reservacion->cancha->club->nombre }}"],
        ["Cancha:", "{{ $reservacion->cancha->nombre }}"],
    ];

    cliente.forEach(([label, value]) => {
        doc.setFont("helvetica", "bold");
        doc.text(label, margin, y);
        doc.setFont("helvetica", "");
        doc.text(value, margin + 40, y);
        y += 7;
    });

    y += 3;

    // Línea separadora
    doc.setDrawColor(180);
    doc.line(margin, y, 148, y);
    y += 10;

    // ===== DETALLES DE LA RESERVA =====
    doc.setFont("helvetica", "bold");
    doc.setFontSize(12);
    doc.text("Detalles de la Reserva", margin, y);
    y += 7;

    const tabla = [
        ["Fecha:", "{{ \Carbon\Carbon::parse($reservacion->reservacion_date)->format('d/m/Y') }}"],
        ["Hora Inicio:", "{{ \Carbon\Carbon::parse($reservacion->hora_inicio)->format('H:i') }}"],
        ["Hora Final:", "{{ \Carbon\Carbon::parse($reservacion->hora_final)->format('H:i') }}"],
        ["Tipo:", "{{ $reservacion->tipoReservacion->franja_horaria }} hora(s)"],
        ["Estado:", "{{ ucfirst($reservacion->status) }}"],
    ];

    // Caja de detalles
    doc.setDrawColor(100);
    doc.rect(margin, y, 125, tabla.length * 10 + 4);

    let boxY = y + 7;

    tabla.forEach(([label, value]) => {
        doc.setFont("helvetica", "bold");
        doc.text(label, margin + 4, boxY);
        doc.setFont("helvetica", "");
        doc.text(value, margin + 45, boxY);
        boxY += 10;
    });

    y = boxY + 10;

    // ===== FOOTER =====
    doc.setFont("helvetica", "italic");
    doc.setFontSize(10);
    doc.text("Este comprobante certifica su reserva en el sistema.", margin, y);

    y += 6;

    doc.text("Emitido el: {{ date('d/m/Y H:i') }}", margin, y);

    // Descargar
    doc.save("comprobante_reserva.pdf");
}
</script>


@endsection
