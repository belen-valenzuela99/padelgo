@extends('layouts.main')

@section('content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<div class="container mx-auto text-center p-6">

    <h1 class="text-2xl font-bold mb-4">Se confirmó tu abono</h1>

    <div class="card shadow-sm w-75 mx-auto mt-4">
        <div class="card-header bg-primary text-white fw-bold">
            Detalles del abono
        </div>
        <div class="card-body p-0">
            <table class="table mb-0 align-middle">
                <tbody>
                    <tr>
                        <th class="bg-light text-end w-25">Usuario</th>
                        <td>{{ $abono->usuario->name ?? 'Desconocido' }}</td>
                    </tr>
                    <tr>
                        <th class="bg-light text-end">Club</th>
                        <td>{{ $abono->cancha->club->nombre ?? 'Sin club' }}</td>
                    </tr>
                    <tr>
                        <th class="bg-light text-end">Cancha</th>
                        <td>{{ $abono->cancha->nombre ?? 'Sin cancha' }}</td>
                    </tr>
                    <tr>
                        <th class="bg-light text-end">Mes</th>
                        <td>{{ ucfirst($abono->mes) }}</td>
                    </tr>
                    <tr>
                        <th class="bg-light text-end">Día de la semana</th>
                        <td>{{ ucfirst($abono->dia_semana) }}</td>
                    </tr>
                    <tr>
                        <th class="bg-light text-end">Hora de Inicio</th>
                        <td>{{ \Carbon\Carbon::parse($abono->hora_inicio)->format('H:i') }}</td>
                    </tr>
                    <tr>
                        <th class="bg-light text-end">Hora de Finalización</th>
                        <td>{{ \Carbon\Carbon::parse($abono->hora_fin)->format('H:i') }}</td>
                    </tr>
                    <tr>
                        <th class="bg-light text-end w-25">Precio Total</th>
                        <td>${{ $abono->precio }}</td>
                    </tr>
                    <tr>
                        <th class="bg-light text-end">Estado</th>
                        <td>{{ $abono->activo ? 'Activo' : 'Inactivo' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="d-flex justify-content-around mt-4">
        <button class="btn btn-success" onclick="descargarTicketAbono()">
            Descargar Ticket PDF
        </button>
        <a href="{{ route('abonos.index') }}" class="btn btn-primary">Volver al listado</a>
    </div>

</div>

<script>
async function descargarTicketAbono() {
    const { jsPDF } = window.jspdf;

    const doc = new jsPDF({
        orientation: "portrait",
        unit: "mm",
        format: "a5"
    });

    const margin = 12;
    let y = margin;

    doc.setFillColor(40, 80, 160);
    doc.rect(0, 0, 210, 35, "F");

    doc.setFont("helvetica", "bold");
    doc.setFontSize(18);
    doc.setTextColor(255, 255, 255);
    doc.text("COMPROBANTE DE ABONO", 12, 22);

    doc.setTextColor(0, 0, 0);
    y = 40;

    doc.setFontSize(12);
    doc.setFont("helvetica", "bold");
    doc.text("Datos del Cliente", margin, y);
    y += 5;

    doc.setFont("helvetica", "");
    doc.setFontSize(11);

    let cliente = [
        ["Usuario:", "{{ $abono->usuario->name }}"],
        ["Club:", "{{ $abono->cancha->club->nombre }}"],
        ["Cancha:", "{{ $abono->cancha->nombre }}"],
    ];

    cliente.forEach(([label, value]) => {
        doc.setFont("helvetica", "bold");
        doc.text(label, margin, y);
        doc.setFont("helvetica", "");
        doc.text(value, margin + 40, y);
        y += 7;
    });

    y += 3;
    doc.setDrawColor(180);
    doc.line(margin, y, 148, y);
    y += 10;

    doc.setFont("helvetica", "bold");
    doc.setFontSize(12);
    doc.text("Detalles del Abono", margin, y);
    y += 7;

    const tabla = [
        ["Mes:", "{{ ucfirst($abono->mes) }}"],
        ["Día:", "{{ ucfirst($abono->dia_semana) }}"],
        ["Hora Inicio:", "{{ \Carbon\Carbon::parse($abono->hora_inicio)->format('H:i') }}"],
        ["Hora Final:", "{{ \Carbon\Carbon::parse($abono->hora_fin)->format('H:i') }}"],
        ["Precio Total:", "${{ $abono->precio }}"],
        ["Estado:", "{{ $abono->activo ? 'Activo' : 'Inactivo' }}"],
    ];

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

    doc.setFont("helvetica", "italic");
    doc.setFontSize(10);
    doc.text("Este comprobante certifica su abono en el sistema.", margin, y);
    y += 6;
    doc.text("Emitido el: {{ date('d/m/Y H:i') }}", margin, y);

    doc.save("comprobante_abono.pdf");
}
</script>
@endsection