<!-- MODAL -->
<div class="modal fade" id="reporteModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">📊 Reportes personalizados</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                {{-- FORMULARIO --}}
                <form id="formReporte">
                    @csrf

                    <div class="row g-3 mb-3">

                        <div class="col-md-3">
                            <label class="form-label">Desde</label>
                            <input type="date" name="fecha_inicio" class="form-control" required>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Hasta</label>
                            <input type="date" name="fecha_fin" class="form-control" required>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Club</label>
                            <select name="club_id" id="clubSelect" class="form-select">
                                <option value="all">Todos</option>
                                @foreach($clubs as $club)
                                    <option value="{{ $club->id }}">{{ $club->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Cancha</label>
                            <select name="cancha_id" id="canchaSelect" class="form-select">
                                <option value="all">Todas</option>
                            </select>
                        </div>

                        {{-- CHECKBOX DE TIPOS DE REPORTE --}}
                        <div class="col-md-12 mt-2">
                            <label class="form-label fw-semibold">
                                Tipo de reporte
                            </label>
                            <div class="d-flex flex-wrap gap-3">

                                <div class="form-check">
                                    <input class="form-check-input"
                                           type="checkbox"
                                           name="tipos[]"
                                           value="ingresos"
                                           checked>
                                    <label class="form-check-label">
                                        Ingresos
                                    </label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input"
                                           type="checkbox"
                                           name="tipos[]"
                                           value="reservas">
                                    <label class="form-check-label">
                                        Reservas por cancha
                                    </label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input"
                                           type="checkbox"
                                           name="tipos[]"
                                           value="abonos">
                                    <label class="form-check-label">
                                        Abonos
                                    </label>
                                </div>

                            </div>
                        </div>
                    </div>

                    <button class="btn btn-success w-100 mt-3">
                        Generar reporte
                    </button>
                </form>

                <hr>

                {{-- RESULTADO (SE INYECTA POR AJAX) --}}
                <div id="resultadoReporte">
                    <div class="text-center text-muted py-4">
                        Complete los filtros y genere el reporte
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>