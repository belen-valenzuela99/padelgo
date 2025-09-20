<section>
    <header class="mb-3">
        <h5 class="mb-1">{{ __('Actualizar Contraseña') }}</h5>
        <p class="text-muted small">
            {{ __('Asegúrate de usar una contraseña larga y segura para mantener tu cuenta protegida.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}">
        @csrf
        @method('put')

        <!-- Contraseña actual -->
        <div class="mb-3">
            <label for="update_password_current_password" class="form-label">{{ __('Contraseña actual') }}</label>
            <input type="password"
                   class="form-control @error('current_password', 'updatePassword') is-invalid @enderror"
                   id="update_password_current_password"
                   name="current_password"
                   autocomplete="current-password">

            @error('current_password', 'updatePassword')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Nueva contraseña -->
        <div class="mb-3">
            <label for="update_password_password" class="form-label">{{ __('Nueva contraseña') }}</label>
            <input type="password"
                   class="form-control @error('password', 'updatePassword') is-invalid @enderror"
                   id="update_password_password"
                   name="password"
                   autocomplete="new-password">

            @error('password', 'updatePassword')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Confirmación de contraseña -->
        <div class="mb-3">
            <label for="update_password_password_confirmation" class="form-label">{{ __('Confirmar contraseña') }}</label>
            <input type="password"
                   class="form-control @error('password_confirmation', 'updatePassword') is-invalid @enderror"
                   id="update_password_password_confirmation"
                   name="password_confirmation"
                   autocomplete="new-password">

            @error('password_confirmation', 'updatePassword')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Botón de guardar -->
        <div class="d-flex align-items-center gap-3">
            <button type="submit" class="btn btn-primary">{{ __('Guardar') }}</button>

            @if (session('status') === 'password-updated')
                <span class="text-success small">{{ __('Guardado.') }}</span>
            @endif
        </div>
    </form>
</section>
