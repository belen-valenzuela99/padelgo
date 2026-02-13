@extends('layouts.main')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Asignar Horarios y Precio a <strong>{{ $cancha->nombre }}</strong></h2>
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('canchas.tipos.update', $cancha->id) }}" method="POST">
        @csrf
        @method('PUT')

        <hr>
        <h5>Crear nuevo horario</h5>

        <div class="row mb-3">
            <div class="col">
                <input type="time" id="nueva_hora_inicio" class="form-control">
            </div>
            <div class="col">
                <input type="time" id="nueva_hora_fin" class="form-control">
            </div>
            <div class="col">
                <input type="number" step="0.01" id="nuevo_precio" class="form-control" placeholder="Precio">
            </div>
            <div class="col"> 
                <button type="button" id="btnAgregarHorario" class="btn btn-success">
                    Agregar
                </button>
            </div>
             <small class="text-muted">
            El horario se crea inactivo. Debe marcarlo y guardar para activarlo.
            </small>


        </div>

        <h5>Seleccione los horarios para activarlos</h5>
        <table class="table table-bordered align-middle">
            <thead>
                <tr>
                   <th>Activo</th>
                    <th>Horario</th>
                    <th>Precio</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>
            @foreach($tipos as $tipo)
            <tr>
                <td>
                    <input type="checkbox"
                        name="tipos[{{ $tipo->id }}][activo]"
                        @checked($tipo->activo)>
                </td>

                <td>{{ $tipo->hora_inicio }} - {{ $tipo->hora_fin }}</td>

                <td>
                    <input type="number"
                        step="0.01"
                        name="tipos[{{ $tipo->id }}][precio]"
                        value="{{ $tipo->precio }}"
                        class="form-control">
                </td>

                <td>
                    <button type="button"
                        class="btn btn-danger btn-sm btnEliminarHorario"
                        data-id="{{ $tipo->id }}">
                        Eliminar
                    </button>
                </td>
            </tr>
            @endforeach
            </tbody>


        </table>

        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        <a href="{{ route('canchas.index') }}" class="btn btn-secondary ms-2">Volver</a>
    </form>
</div>

<script>
document.getElementById("btnAgregarHorario").addEventListener("click", async function () {

    const horaInicio = document.getElementById("nueva_hora_inicio").value;
    const horaFin = document.getElementById("nueva_hora_fin").value;
    const precio = document.getElementById("nuevo_precio").value;

    if (!horaInicio || !horaFin || !precio) {
        alert("Complete todos los campos");
        return;
    }

    const canchaId = {{ $cancha->id }};

    try {
        const response = await fetch(`/canchas/${canchaId}/crear-horario`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                hora_inicio: horaInicio,
                hora_fin: horaFin,
                precio: precio
            })
        });

        const data = await response.json();

        if (!response.ok) {
            alert(data.message || "Error al crear el horario");
            return;
        }

        if (data.success) {
            location.reload();
        }

    } catch (error) {
        console.error(error);
        alert("Error inesperado");
    }
});

document.querySelectorAll(".btnEliminarHorario").forEach(btn => {
    btn.addEventListener("click", async function () {

        if (!confirm("¿Está seguro de eliminar este horario?")) {
            return;
        }

        const tipoId = this.dataset.id;
        const canchaId = {{ $cancha->id }};

        try {
            const response = await fetch(
                `/canchas/${canchaId}/horario/${tipoId}`,
                {
                    method: "DELETE",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    }
                }
            );

            const data = await response.json();

            if (!response.ok) {
                alert(data.message || "Error al eliminar");
                return;
            }

            if (data.success) {
                location.reload();
            }

        } catch (error) {
            console.error(error);
            alert("Error inesperado");
        }

    });
});
</script>

@endsection
