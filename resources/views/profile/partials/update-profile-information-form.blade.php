<section>
    <header class="mb-3">
        <h5 class="mb-1">{{ __('Información del Perfil') }}</h5>
        <p class="text-muted small">
            {{ __("Actualiza la información de tu cuenta y tu dirección de correo electrónico.") }}
        </p>
    </header>

    <!-- Form para reenviar verificación -->
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <!-- Form principal -->
    <form method="post" action="{{ route('profile.update') }}">
        @csrf
        @method('patch')

        <!-- Nombre -->
        <div class="mb-3">
            <label for="name" class="form-label">{{ __('Nombre') }}</label>
            <input type="text" 
                   class="form-control @error('name') is-invalid @enderror"
                   id="name"
                   name="name"
                   value="{{ old('name', $user->name) }}"
                   required autofocus autocomplete="name">

            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Email -->
        <div class="mb-3">
            <label for="email" class="form-label">{{ __('Correo electrónico') }}</label>
            <input type="email" 
                   class="form-control @error('email') is-invalid @enderror"
                   id="email"
                   name="email"
                   value="{{ old('email', $user->email) }}"
                   required autocomplete="username">

            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="alert alert-warning mt-2">
                    {{ __('Tu dirección de correo no está verificada.') }}
                    <button form="send-verification" class="btn btn-link p-0">
                        {{ __('Haz clic aquí para reenviar el correo de verificación.') }}
                    </button>
                </div>

                @if (session('status') === 'verification-link-sent')
                    <div class="alert alert-success mt-2">
                        {{ __('Se ha enviado un nuevo enlace de verificación a tu correo.') }}
                    </div>
                @endif
            @endif
        </div>

        <!-- Botón de guardar -->
        <div class="d-flex align-items-center gap-3">
            <button type="submit" class="btn btn-success">{{ __('Guardar') }}</button>

            @if (session('status') === 'profile-updated')
                <span class="text-success small">{{ __('Guardado.') }}</span>
            @endif
        </div>
    </form>
</section>
