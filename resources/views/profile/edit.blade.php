@extends('layouts.main')

@section('title', 'Perfil')

@section('content')
    <div class="container my-4">
        <h2 class="mb-4">Perfil</h2>

        <div class="row g-4">
            <!-- Formulario de actualización de información -->
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>
            </div>

            <!-- Formulario de actualización de contraseña -->
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>
            </div>

            <!-- Formulario de eliminar usuario -->
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
