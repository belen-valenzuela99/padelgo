@extends('layouts.main')

@section('title', 'Dashboard')

@section('content')
    <div class="py-4">
        <h2 class="mb-4">Dashboard</h2>

        <div class="container">
            <div class="card shadow-sm">
                <div class="card-body">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>
@endsection
