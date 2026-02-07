@extends('layouts.main')

@section('title', 'Dashboard Admin')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <h1>Bienvenido al Dashboard Admin</h1>
    <div class="container py-4">

    {{-- KPIs --}}
    <div class="row mb-4">
        <x-kpi title="Clubes" :value="$totalClubes" />
        <x-kpi title="Usuarios" :value="$totalUsuarios" />
        <x-kpi title="Canchas activas" :value="$totalCanchas" />
        <x-kpi title="Admins" :value="$usuariosPorRol['admin'] ?? 0" />
        <x-kpi title="Gestores" :value="$usuariosPorRol['gestor'] ?? 0" />
        <x-kpi title="Jugadores" :value="$usuariosPorRol['jugador'] ?? 0" />
    </div>

    {{-- GRAFICOS --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <canvas id="usuariosPorRolChart"></canvas>
        </div>

        <div class="col-md-4">
            <canvas id="clubesCanchasChart"></canvas>
        </div>

        <div class="col-md-4">
            <canvas id="usuariosPorMesChart"></canvas>
        </div>
    </div>

    {{-- TABLAS --}}
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header">Últimos clubes</div>
                <table class="table table-sm mb-0">
                    <thead>
                        <tr>
                            <th>Club</th>
                            <th>Gestor</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ultimosClubes as $club)
                        <tr>
                            <td>{{ $club->nombre }}</td>
                            <td>{{ $club->gestor->name ?? 'Sin asignar' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header">Últimos usuarios</div>
                <table class="table table-sm mb-0">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Rol</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ultimosUsuarios as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ ucfirst($user->role) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ALERTAS --}}
    <div class="row">
        <div class="col-md-6">
            <div class="alert alert-warning">
                Clubes sin gestor asignado: <strong>{{ $clubesSinGestor }}</strong>
            </div>
        </div>
        <div class="col-md-6">
            <div class="alert alert-danger">
                Gestores sin club: <strong>{{ $gestoresSinClub }}</strong>
            </div>
        </div>
    </div>

</div>

    <div class="container_card">
        
            <div class="card" style="width: 18rem;">
  <img src="{{ asset("img/admin.png") }}" class="card-img-top" alt="...">
  <div class="card-body">
    <h5 class="card-title">Clubes</h5>
    <p class="card-text">Gestione los club y asignale un gestor</p>
    <a href="{{ route('clubes.index') }}" class="btn btn-primary">Gestionar</a>
  </div>
</div>

<div class="card" style="width: 18rem;">
  <img src="{{ asset("img/user.svg") }}" class="card-img-top" alt="...">
  <div class="card-body">
    <h5 class="card-title">Usuarios</h5>
    <p class="card-text">Gestione los usuarios y asignele un rol</p>
    <a href="{{ route('admin.users.index') }}" class="btn btn-primary">Gestionar</a>
  </div>
</div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const rolesLabels = @json($rolesLabels);
const rolesData   = @json($rolesData);

new Chart(document.getElementById('usuariosPorRolChart'), {
    type: 'pie',
    data: {
        labels: rolesLabels,
        datasets: [{ data: rolesData }]
    }
});

new Chart(document.getElementById('clubesCanchasChart'), {
    type: 'bar',
    data: {
        labels: {!! json_encode($clubesConCanchas->pluck('nombre')) !!},
        datasets: [{
            label: 'Canchas activas',

            data: {!! json_encode($clubesConCanchas->pluck('canchas_count')) !!}
        }]
    }
});

new Chart(document.getElementById('usuariosPorMesChart'), {
    type: 'line',
    data: {
        labels: {!! json_encode($usuariosPorMes->pluck('mes')) !!},
        datasets: [{
          label: 'Clubes creados',

            data: {!! json_encode($usuariosPorMes->pluck('total')) !!}
        }]
    }
});
</script>

@endsection
