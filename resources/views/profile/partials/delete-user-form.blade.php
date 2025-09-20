<section>
    <header class="mb-3">
        <h5 class="mb-1 text-danger">{{ __('Eliminar cuenta') }}</h5>
        <p class="text-muted small">
            {{ __('Una vez eliminada, todos los datos de tu cuenta se borrarán permanentemente. Descarga cualquier información que desees conservar antes de continuar.') }}
        </p>
    </header>

    <!-- Botón que abre el modal -->
    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmUserDeletion">
        {{ __('Eliminar cuenta') }}
    </button>

    <!-- Modal de confirmación -->
    <div class="modal fade" id="confirmUserDeletion" tabindex="-1" aria-labelledby="confirmUserDeletionLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('delete')

                    <div class="modal-header">
                        <h5 class="modal-title text-danger" id="confirmUserDeletionLabel">
                            {{ __('¿Estás seguro de que quieres eliminar tu cuenta?') }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('Cerrar') }}"></button>
                    </div>

                    <div class="modal-body">
                        <p class="mb-3">
                            {{ __('Una vez eliminada, todos los datos de tu cuenta se borrarán permanentemente. Ingresa tu contraseña para confirmar.') }}
                        </p>

                        <!-- Campo de contraseña -->
                        <div class="mb-3">
                            <label for="password" class="form-label">{{ __('Contraseña') }}</label>
                            <input type="password"
                                   id="password"
                                   name="password"
                                   class="form-control @error('password', 'userDeletion') is-invalid @enderror"
                                   placeholder="{{ __('Contraseña') }}">

                            @error('password', 'userDeletion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            {{ __('Cancelar') }}
                        </button>
                        <button type="submit" class="btn btn-danger">
                            {{ __('Eliminar cuenta') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
