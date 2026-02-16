@extends('layouts.main')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Clubes</h2>
        @if (auth()->user()->role === 'admin') 
        <a href="{{ route('clubes.create') }}" class="btn btn-primary">Crear Club</a>
        @else

        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm border-0">
    <div class="card-body p-0">

        <div class="table-responsive">
            <table class="table table-bordered  table-hover align-middle mb-0">

                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Imagen</th>
                        <th>Nombre</th>
                        <th>Gestor</th>
                        <th>Descripción</th>
                        <th>Dirección</th>
                        <th>Ubicación</th>
                        <th class="text-end pe-4">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($clubes as $club)
                        <tr>

                            <td class="fw-semibold text-muted">
                                #{{ $club->id }}
                            </td>

                            <td>
                                @if($club->img)
                                    <img src="{{ asset($club->img) }}"
                                         alt="Imagen del club"
                                         class="rounded shadow-sm"
                                         style="width: 90px; height: 60px; object-fit: cover;">
                                @else
                                    <span class="text-muted">Sin imagen</span>
                                @endif
                            </td>

                            <td class="fw-semibold">
                                {{ $club->nombre }}
                            </td>

                            <td>
                                {{ $club->id_user }}
                            </td>

                            <td class="text-muted small" style="max-width: 220px;">
                                {{ $club->descripcion }}
                            </td>

                            <td>
                                {{ $club->direccion }}
                            </td>

                            <td>
                                @if($club->mapa)
                                    <button 
                                        type="button" 
                                        class="btn btn-sm btn-outline-primary"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#mapModal"
                                        data-mapa="{{ $club->mapa }}"
                                    >
                                        Ver mapa
                                    </button>
                                @else
                                    <span class="text-muted">Sin ubicación</span>
                                @endif
                            </td>

                            <td class="text-end pe-4">
                                <a href="{{ route('clubes.edit', $club->id) }}"
                                   class="btn btn-sm btn-outline-warning me-2">
                                    Editar
                                </a>

                                @if (auth()->user()->role === 'admin')
                                    <form action="{{ route('clubes.destroy', $club->id) }}"
                                          method="POST"
                                          class="d-inline"
                                          onsubmit="return confirm('¿Estás seguro de eliminar este club?');">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn btn-sm btn-outline-danger">
                                            Eliminar
                                        </button>
                                    </form>
                                @endif
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">
                                No hay clubes registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

    </div>
</div>

</div>

<!-- Button trigger modal -->


<!-- Modal -->
<div class="modal fade" id="mapModal" tabindex="-1" aria-labelledby="mapModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="mapModalLabel">Ubicación del club</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body" style="height:400px;">
        <iframe 
            id="mapIframe"
            src=""
            width="100%" 
            height="100%" 
            style="border:0;" 
            allowfullscreen="" 
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade">
        </iframe>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const mapModal = document.getElementById('mapModal');
    const iframe = document.getElementById('mapIframe');

    mapModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget; 
        const mapaUrl = button.getAttribute('data-mapa');

        // Cargamos la URL del mapa en el iframe
        iframe.src = mapaUrl;
    });

    // Cuando se cierra el modal, limpiamos el iframe
    mapModal.addEventListener('hidden.bs.modal', function () {
        iframe.src = "";
    });
});
</script>

@endsection
