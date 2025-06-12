
<main id="content" class="container mt-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-primary fw-bold">
                <i class="bi bi-journal-check me-2"></i>Reporte de Sacramentos Completados
            </h5>
            <div class="w-25">
                <select id="filtroSacramento" class="form-select form-select-sm">
                    <option value="">Todos los sacramentos</option>
                    <?php
                  
                     
                    $sacramentos = $pdo->query("SELECT id_sacramento, nombre FROM sacramentos")->fetchAll();
                    foreach ($sacramentos as $s) {
                        echo "<option value='{$s['id_sacramento']}'>{$s['nombre']}</option>";
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle mb-0">
                    <thead class="table-light text-center">
                        <tr>
                            <th>#</th>
                            <th>Nombre completo</th>
                            <th>Género</th>
                            <th>Teléfono</th>
                            <th>Dirección</th>
                            <th>Sacramento</th>
                            <th>Fecha</th>
                            <th>Lugar</th>
                        </tr>
                    </thead>
                    <tbody id="tbodyReporte">
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <div class="spinner-border text-primary" role="status"></div>
                                <p class="mt-2 mb-0">Cargando datos...</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const filtro = document.getElementById('filtroSacramento');
    const tbody = document.getElementById('tbodyReporte');

    const cargarReporte = (id_sacramento = '') => {
        fetch(`php/reporte_sacramento.php${id_sacramento ? '?id_sacramento=' + id_sacramento : ''}`)
            .then(res => res.json())
            .then(data => {
                tbody.innerHTML = '';
                if (data.length === 0) {
                    tbody.innerHTML = `<tr>
                        <td colspan="8" class="text-center text-muted py-4">
                            <i class="bi bi-emoji-frown fs-3"></i><br>No se encontraron registros.
                        </td>
                    </tr>`;
                    return;
                }

                data.forEach((item, i) => {
                    tbody.innerHTML += `
                        <tr class="text-center">
                            <td>${i + 1}</td>
                            <td class="text-start">${item.nombre} ${item.apellido}</td>
                            <td>${item.genero}</td>
                            <td>${item.telefono || '-'}</td>
                            <td class="text-start">${item.direccion || '-'}</td>
                            <td>${item.sacramento}</td>
                            <td>${item.fecha || '-'}</td>
                            <td>${item.lugar || '-'}</td>
                        </tr>
                    `;
                });
            })
            .catch(() => {
                tbody.innerHTML = `<tr><td colspan="8" class="text-center text-danger py-4">
                    <i class="bi bi-exclamation-triangle fs-4"></i><br>Error al cargar el reporte.
                </td></tr>`;
            });
    };

    filtro.addEventListener('change', () => {
        cargarReporte(filtro.value);
    });

    cargarReporte(); // carga inicial
});
</script>
