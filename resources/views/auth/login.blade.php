<x-guest-layout>

    <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="card shadow p-4" style="max-width: 420px; width: 100%; background-color: #d8e4ff; border: none;">

            <!-- Logo -->
            <div class="text-center mb-3 d-flex justify-content-center">
                <img src="{{ asset('img/frontend/logo.jpg') }}"
                     alt="PadelGo Logo"
                     class="rounded-circle"
                     style="width: 90px; height: 90px; object-fit: cover;">
            </div>

            <h4 class="text-center mb-4" style="color: #032D3C;">Iniciar sesión</h4>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email -->
                <div class="mb-3">
                    <label class="form-label fw-semibold" for="email" style="color: #07091b;">Email</label>
                    <input type="email"
                           id="email"
                           name="email"
                           value="{{ old('email') }}"
                           required
                           autofocus
                           class="form-control border-2"
                           style="border: none ; color: #032D3C;">
                    <x-input-error :messages="$errors->get('email')" class="mt-1" />
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label class="form-label fw-semibold" for="password" style="color: #032D3C;">Contraseña</label>
                    <input type="password"
                           id="password"
                           name="password"
                           required
                           class="form-control border-2"
                           style="border: none; color: #000000;;">
                    <x-input-error :messages="$errors->get('password')" class="mt-1" />
                </div>

                <!-- Remember me -->
                <div class="form-check mb-3">
                    <input type="checkbox" id="remember_me" name="remember" class="form-check-input">
                    <label class="form-check-label" for="remember_me" style="color: #032D3C;">
                        Recordarme
                    </label>
                </div>

                <!-- Forgot password -->
                @if (Route::has('password.request'))
                    <div class="text-end mb-3">
                        <a href="{{ route('password.request') }}" style="color: #032D3C; text-decoration: underline;">
                            ¿Olvidaste tu contraseña?
                        </a>
                    </div>
                @endif

                <!-- Submit -->
                <button type="submit"
                        class="btn w-100 fw-semibold"
                        style="background-color: #748dfd; color: #032D3C;">
                    Iniciar sesión
                </button>

            </form>

        </div>
    </div>

</x-guest-layout>
