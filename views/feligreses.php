<?php
$buscar = $_GET['buscar'] ?? '';

// Consulta de parroquias
$parroquias = $pdo->query("SELECT * FROM parroquias")->fetchAll(PDO::FETCH_ASSOC);

// Consulta de feligreses con filtro de búsqueda
$sql = "SELECT f.*, p.nombre AS nombre_parroquia 
        FROM feligreses f 
        LEFT JOIN parroquias p ON f.id_parroquia = p.id_parroquia
        WHERE f.nombre LIKE :buscar OR f.apellido LIKE :buscar
        ORDER BY f.id_feligres DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute(['buscar' => "%$buscar%"]);
$feligreses = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Consulta de parroquias
$parroquias = $pdo->query("SELECT * FROM parroquias")->fetchAll(PDO::FETCH_ASSOC);

// Consulta de sacramentos
$sacramentos = getSacramentos($pdo);

?>

<main id="content" class="container mt-4">
    <h2 class="mb-4"><i class="bi bi-people-fill me-2"></i>Gestión de Feligreses</h2>

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
        <form class="d-flex flex-grow-1 me-2" method="GET">
            <input class="form-control me-2" type="search" name="buscar" value="<?= htmlspecialchars($buscar) ?>"
                placeholder="Buscar por nombre o apellido...">
            <button class="btn btn-outline-primary" type="submit"><i class="bi bi-search"></i></button>
        </form>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalRegistro">
            <i class="bi bi-plus-lg me-1"></i>Nuevo Feligres
        </button>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Nombre Completo</th>
                    <th>Parroquia</th>
                    <th>Fecha Nacimiento</th>
                    <th>Género</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($feligreses) === 0): ?>
                    <tr>
                        <td colspan="8" class="text-center">No se encontraron resultados.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($feligreses as $f): ?>
                        <tr>
                            <td><?= $f['id_feligres'] ?></td>
                            <td><?= htmlspecialchars($f['nombre'] . ' ' . $f['apellido']) ?></td>
                            <td><?= htmlspecialchars($f['nombre_parroquia']) ?></td>
                            <td><?= $f['fecha_nacimiento'] ? date('d/m/Y', strtotime($f['fecha_nacimiento'])) : '' ?></td>
                            <td><?= $f['genero'] === 'M' ? 'Masculino' : ($f['genero'] === 'F' ? 'Femenino' : '') ?></td>

                            <td class="text-center">
                                <!-- Ver Detalle -->
                                <button class="btn btn-outline-info btn-sm rounded-circle"
                                    onclick="verDetalleFeligres(<?= $f['id_feligres'] ?>)" data-bs-toggle="tooltip"
                                    data-bs-placement="top" title="Ver Detalle">
                                    <i class="bi bi-eye"></i>
                                </button>

                                <!-- Ver DNI Parroquial -->
                                <button class="btn btn-outline-primary btn-sm rounded-circle"
                                    onclick="abrirModalDNIParroquial(<?= $f['id_feligres'] ?>)" data-bs-toggle="tooltip"
                                    data-bs-placement="top" title="Ver DNI Parroquial">
                                    <i class="bi bi-credit-card-2-front"></i>
                                </button>

                                <!-- Asignar a Catequesis -->
                                <button class="btn btn-outline-secondary btn-sm rounded-circle"
                                    onclick="abrirModalAsignarCatequesis(<?= $f['id_feligres'] ?>)" data-bs-toggle="modal"
                                    data-bs-target="#modalAsignarCatequesis" data-bs-toggle="tooltip" data-bs-placement="top"
                                    title="Asignar a Catequesis">
                                    <i class="bi bi-bookmark-plus"></i>
                                </button>

                                <!-- Asignar Sacramento -->
                               <!--  <button class="btn btn-outline-success btn-sm rounded-circle"
                                    onclick="abrirModalAsignar(<?= $f['id_feligres'] ?>)" data-bs-toggle="tooltip"
                                    data-bs-placement="top" title="Asignar Sacramento">
                                    <i class="bi bi-plus-circle"></i>
                                </button> -->

                                <!-- Ver Sacramentos -->
                                <button class="btn btn-outline-dark btn-sm rounded-circle"
                                    onclick="abrirModalVer(<?= $f['id_feligres'] ?>)" data-bs-toggle="tooltip"
                                    data-bs-placement="top" title="Ver Sacramentos">
                                    <i class="bi bi-book"></i>
                                </button>

                                <!-- Eliminar -->
                                <a href="../php/eliminar_feligres.php?id=<?= $f['id_feligres'] ?>"
                                    class="btn btn-outline-danger btn-sm rounded-circle"
                                    onclick="return confirm('¿Eliminar este feligrés?')" data-bs-toggle="tooltip"
                                    data-bs-placement="top" title="Eliminar">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>

                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Aquí se pueden incluir los modales para registro y edición reutilizando código -->
</main>
<!-- Modal de Registro de Feligres -->
<div class="modal fade" id="modalRegistro" tabindex="-1" aria-labelledby="modalRegistroFeligresLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalRegistroFeligresLabel">Registro de Feligres</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <form id="formRegistroFeligres">
                    <!-- 1. Selección de Parroquia -->
                    <div class="mb-3">
                        <label for="parroquia" class="form-label">Parroquia</label>
                        <select class="form-select" id="parroquia" name="parroquia" required>
                            <option value="">Seleccione una parroquia</option>
                            <?php foreach ($parroquias as $p): ?>
                                <option value="<?= (int) $p['id_parroquia'] ?>"><?= htmlspecialchars($p['nombre']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>


                    <!-- 2. Datos del feligrés -->
                    <div id="datosFeligres" class="border rounded p-3 mb-3 d-none">
                        <h6 class="mb-3 text-primary">Datos del Feligres</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <input type="text" class="form-control" placeholder="Nombre" id="nombre" required>
                            </div>
                            <div class="col-md-6">
                                <input type="text" class="form-control" placeholder="Apellido" id="apellido" required>
                            </div>
                            <div class="col-md-6">
                                <input type="date" class="form-control" id="fechaNacimiento" required>
                            </div>
                            <div class="col-md-6">
                                <select class="form-select" id="genero" required>
                                    <option value="">Seleccione género</option>
                                    <option value="M">Masculino</option>
                                    <option value="F">Femenino</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <input type="text" class="form-control" placeholder="Dirección" id="direccion" required>
                            </div>
                            <!-- Sacramento -->
                            <div class="col-12">
                                <label for="sacramento" class="form-label mt-3">Tipo de Sacramento</label>
                                <select class="form-select" id="sacramento" name="sacramento" required>
                                    <option value="">Seleccione sacramento</option>
                                    <?php foreach ($sacramentos as $s): ?>
                                        <option data-sacramento="<?= htmlspecialchars($s['nombre']) ?>"
                                            value="<?= $s['id_sacramento'] ?>"><?= htmlspecialchars($s['nombre']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                        </div>
                    </div>

                    <!-- 3. Campos adicionales por sacramento -->
                    <div id="camposAdicionales" class="d-none">

                        <!-- Padres -->
                        <div id="seccionPadres" class="border rounded p-3 mb-3 d-none">
                            <h6 class="text-primary">Datos de los Padres</h6>
                            <div class="row g-3">
                                <!-- Padre -->
                                <div class="col-md-4">
                                    <input type="text" class="form-control" placeholder="Nombre del Padre"
                                        id="nombrePadre">
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" placeholder="Apellido del Padre"
                                        id="apellidoPadre">
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" placeholder="Teléfono del Padre"
                                        id="telefonoPadre">
                                </div>

                                <!-- Madre -->
                                <div class="col-md-4">
                                    <input type="text" class="form-control" placeholder="Nombre de la Madre"
                                        id="nombreMadre">
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" placeholder="Apellido de la Madre"
                                        id="apellidoMadre">
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" placeholder="Teléfono de la Madre"
                                        id="telefonoMadre">
                                </div>
                            </div>
                        </div>

                        <!-- Padrinos -->
                        <div id="seccionPadrinos" class="border rounded p-3 mb-3 d-none">
                            <h6 class="text-primary">Datos de los Padrinos</h6>
                            <div class="row g-3" id="camposPadrinos">
                                <!-- Padrino -->
                                <div class="col-md-4"><input type="text" class="form-control"
                                        placeholder="Nombre del Padrino" id="nombrePadrino"></div>
                                <div class="col-md-4"><input type="text" class="form-control"
                                        placeholder="Apellido del Padrino" id="apellidoPadrino"></div>
                                <div class="col-md-4"><input type="text" class="form-control"
                                        placeholder="Teléfono del Padrino" id="telefonoPadrino"></div>
                                <!-- Madrina (solo bautismo) -->
                                <div class="col-md-4 d-none" id="madrinaCampos">
                                    <input type="text" class="form-control mb-2" placeholder="Nombre de la Madrina"
                                        id="nombreMadrina">
                                    <input type="text" class="form-control mb-2" placeholder="Apellido de la Madrina"
                                        id="apellidoMadrina">
                                    <input type="text" class="form-control" placeholder="Teléfono de la Madrina"
                                        id="telefonoMadrina">
                                </div>
                            </div>
                        </div>

                        <!-- Matrimonio -->
                        <div id="seccionMatrimonio" class="border rounded p-3 mb-3 d-none">
                            <h6 class="text-primary">Datos del Matrimonio</h6>
                            <div class="row g-3">
                                <div class="col-md-6"><input type="text" class="form-control"
                                        placeholder="Teléfono del Contrayente" id="telefonoMatrimonio"></div>
                                <div class="col-md-6">
                                    <select class="form-select" id="estadoCivil">
                                        <option value="Soltero" selected>Soltero</option>
                                        <!-- <option value="Casado">Casado</option>
                                        <option value="Viudo">Viudo</option> -->
                                    </select>
                                </div>
                            </div>
                            <hr>
                            <h6 class="text-primary">Padrinos y Testigos</h6>
                            <div class="row g-3">
                                <div class="col-md-4"><input type="text" class="form-control"
                                        placeholder="Nombre del Padrino" id="nombrePadrinoMat"></div>
                                <div class="col-md-4"><input type="text" class="form-control"
                                        placeholder="Apellido del Padrino" id="apellidoPadrinoMat"></div>
                                <div class="col-md-4"><input type="text" class="form-control"
                                        placeholder="Teléfono del Padrino" id="telefonoPadrinoMat"></div>
                                <div class="col-md-4"><input type="text" class="form-control"
                                        placeholder="Nombre del Testigo" id="nombreTestigo"></div>
                                <div class="col-md-4"><input type="text" class="form-control"
                                        placeholder="Apellido del Testigo" id="apellidoTestigo"></div>
                                <div class="col-md-4"><input type="text" class="form-control"
                                        placeholder="Teléfono del Testigo" id="telefonoTestigo"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="mt-4 text-end">
                        <button type="submit" class="btn btn-success">Registrar</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



<!--  Asignacion de catequesis -->
<div class="modal fade" id="modalAsignarCatequesis" tabindex="-1" aria-labelledby="modalAsignarCatequesisLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <form id="formAsignarCatequesis">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAsignarCatequesisLabel">Asignar Catequesis y Curso</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="idFeligresAsignar" name="id_feligres" />

                    <div class="mb-3">
                        <label for="selectCatequesis" class="form-label">Catequesis</label>
                        <select id="selectCatequesis" name="id_catequesis" class="form-select" required>
                            <option value="">Seleccione una catequesis</option>
                            <!-- Opciones dinámicas -->
                        </select>
                    </div>

                    <div class="mb-3 d-none" id="divCursos">
                        <label for="selectCurso" class="form-label">Curso</label>
                        <select id="selectCurso" name="id_curso" class="form-select">
                            <option value="">Seleccione un curso</option>
                            <!-- Opciones dinámicas -->
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="fechaInscripcion" class="form-label">Fecha de Inscripción</label>
                        <input type="date" id="fechaInscripcion" name="fecha_inscripcion" class="form-control" required>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Guardar asignación</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </form>
    </div>
</div>


<!-- Modal Asignar Sacramento -->
<div class="modal fade" id="modalAsignarSacramento" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="formAsignarSacramento">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Asignar Sacramento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id_feligres" id="sacramento_id_feligres">
                    <div class="mb-3">
                        <label for="id_sacramento" class="form-label">Sacramento</label>
                        <select class="form-select" name="id_sacramento" id="id_sacramento" required>
                            <!-- Opciones se cargan dinámicamente -->
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="fecha" class="form-label">Fecha</label>
                        <input type="date" class="form-control" name="fecha" required>
                    </div>
                    <div class="mb-3">
                        <label for="lugar" class="form-label">Lugar</label>
                        <input type="text" class="form-control" name="lugar" required>
                    </div>
                    <div class="mb-3">
                        <label for="observaciones" class="form-label">Observaciones</label>
                        <textarea class="form-control" name="observaciones" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button class="btn btn-success" type="submit">Guardar</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Ver Sacramentos -->
<div class="modal fade" id="modalVerSacramentos" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Sacramentos del Feligrés</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="listaSacramentos"></div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>



<!-- Modal Detalle Feligres -->
<div class="modal fade" id="modalDetalleFeligres" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Detalle del Feligrés</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="detalleContainer"></div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para seleccionar sacramento -->
<div class="modal fade" id="modalDNIParroquial" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="bi bi-person-vcard me-2"></i>DNI Parroquial</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="contenidoDNIParroquial" class="p-3 border rounded shadow-sm bg-light">
                    <!-- Aquí se inyecta dinámicamente la tarjeta del DNI -->
                    <div id="tarjetaParroquial" class="tarjeta mt-2"></div>
                </div>

                <!-- Botón para imprimir la tarjeta -->
                <div class="text-end mt-3">
                    <button onclick="imprimirTarjeta()" class="btn btn-outline-secondary">
                        <i class="bi bi-printer me-1"></i> Imprimir tarjeta
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    function tolstipsAcciones() {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    };

    function abrirModalDNIParroquial(idFeligres) {
        fetch(`php/obtener_dni_parroquial.php?id=${idFeligres}`)
            .then(res => res.json())
            .then(data => {
                if (data.error) {
                    alert(data.error);
                    return;
                }

                const { feligres, telefono_final, sacramentos } = data;

                let sacramentoSeleccionado = sacramentos[0]; // Selecciona el primero por defecto

                if (sacramentos.length > 1) {
                    // Si hay más de un sacramento, se puede agregar un selector
                    let select = `<select class="form-select mb-2" id="selectorSacramento">`;
                    sacramentos.forEach(s => {
                        select += `<option value="${s.id_sacramento}">${s.nombre} - (${s.estado})</option>`;
                    });
                    select += `</select>`;
                    document.getElementById('contenidoDNIParroquial').innerHTML = select;
                    document.getElementById('selectorSacramento').addEventListener('change', e => {
                        const selectedId = parseInt(e.target.value);
                        sacramentoSeleccionado = sacramentos.find(s => s.id_sacramento == selectedId);
                        renderDNI(feligres, telefono_final, sacramentoSeleccionado);
                    });
                }

                renderDNI(feligres, telefono_final, sacramentoSeleccionado);
                const modal = new bootstrap.Modal(document.getElementById('modalDNIParroquial'));
                modal.show();
            });
    }

    function renderDNI(feligres, telefono, sacramento) {
        const contenedor = document.getElementById('tarjetaParroquial');
        const edad = calcularEdad(feligres.fecha_nacimiento);

        contenedor.innerHTML = `
    <div class="text-center">
      <div class="foto">
        <i class="bi bi-person-fill"></i>
      </div>
      <h3>${feligres.nombre} ${feligres.apellido}</h3>
    </div>
    <div class="dato"><strong>Parroquia:</strong> ${feligres.parroquia}</div>
    <div class="dato"><strong>Sacramento:</strong> ${sacramento.nombre} (${sacramento.estado})</div>
    <div class="dato"><strong>Fecha de Nacimiento:</strong> ${formatearFecha(feligres.fecha_nacimiento)} (${edad} años)</div>
    <div class="dato"><strong>Género:</strong> ${feligres.genero}</div>
    <div class="dato"><strong>Teléfono:</strong> ${telefono}</div>
    <div class="text-end mt-2 small text-muted">
      DNI parroquial generado por el sistema
    </div>
  `;
    }

    function calcularEdad(fechaNac) {
        const f = new Date(fechaNac);
        const diff = Date.now() - f.getTime();
        return Math.floor(diff / (1000 * 60 * 60 * 24 * 365.25));
    }

    function formatearFecha(fecha) {
        if (!fecha) return '';
        const d = new Date(fecha);
        return d.toLocaleDateString('es-ES');
    }

    function imprimirTarjeta() {
        const contenido = document.getElementById('tarjetaParroquial').innerHTML;

        const ventana = window.open('', '_blank');
        ventana.document.write(`
    <html>
    <head>
      <title>Imprimir Tarjeta Parroquial</title>
      <style>
        body {
          font-family: Arial, sans-serif;
          padding: 20px;
        }
        .tarjeta {
          border: 2px solid #0d6efd;
          border-radius: 10px;
          padding: 20px;
          max-width: 500px;
          margin: auto;
          box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .tarjeta h3 {
          margin-top: 0;
          color: #0d6efd;
        }
        .foto {
          width: 100px;
          height: 100px;
          background-color: #dee2e6;
          border-radius: 50%;
          display: flex;
          align-items: center;
          justify-content: center;
          font-size: 40px;
          color: #6c757d;
          margin: auto;
          margin-bottom: 15px;
        }
        .dato {
          margin-bottom: 6px;
        }
        @media print {
          body {
            margin: 0;
          }
        }
      </style>
    </head>
    <body onload="window.print(); window.close();">
      <div class="tarjeta">
        ${contenido}
      </div>
    </body>
    </html>
  `);
    }


    function verDetalleFeligres(id) {
        fetch(`php/obtener_detalle_feligres.php?id=${id}`)
            .then(res => res.json())
            .then(data => {
                if (data.error) return alert(data.error);

                const c = data;
                let html = `
        <h5>Datos personales</h5><ul>`;
                for (const key of ['nombre', 'apellido', 'fecha_nacimiento', 'genero', 'direccion', 'telefono', 'estado_civil']) {
                    if (c[key]) html += `<li><strong>${key.replace('_', ' ')}:</strong> ${c[key]}</li>`;
                }
                html += `</ul><hr><h5>Sacramentos</h5>`;
                if (c.sacramentos.length) {
                    c.sacramentos.forEach(s => {
                        const pct = s.estado === 'completado' ? 100 : (s.estado === 'en_proceso' ? 50 : 10);
                        html += `
            <div><strong>${s.nombre}</strong> (${s.fecha || 'sin fecha'})
              <div class="progress"><div class="progress-bar bg-${s.estado === 'completado' ? 'success' : 'warning'}" style="width:${pct}%">${s.estado}</div></div>
            </div>`;
                    });
                } else html += `<em>No tiene sacramentos</em>`;
                html += `<hr><h5>Parientes</h5>`;
                if (c.parientes.length) {
                    html += `<ul>`;
                    c.parientes.forEach(p => html += `<li>${p.tipo_relacion}: ${p.nombre} ${p.apellido} (sacramento: ${p.sacramento || 'N/A'})</li>`);
                    html += `</ul>`;
                } else html += `<em>No hay parientes</em>`;
                html += `<hr><h5>Cursos inscritos</h5>`;
                if (c.cursos.length) {
                    c.cursos.forEach(cr => {
                        const pct = cr.estado === 'completado' ? 100 : (cr.estado === 'en_proceso' ? 50 : 10);
                        html += `
            <div class="border p-2 mb-2">
              <strong>${cr.nombre}</strong> (${cr.fecha_inicio} - ${cr.fecha_fin})
              <div class="progress mt-1"><div class="progress-bar bg-${cr.estado === 'completado' ? 'success' : 'warning'}" style="width:${pct}%">${cr.estado}</div></div>
              <small>Catequistas: ${cr.catequistas.join(', ') || '—'}</small>
            </div>`;
                    });
                } else html += `<em>No está inscrito en cursos</em>`;

                document.getElementById('detalleContainer').innerHTML = html;
                new bootstrap.Modal(document.getElementById('modalDetalleFeligres')).show();
            })
            .catch(err => alert("Error al cargar datos: " + err));
    }


    function abrirModalAsignar(idFeligres) {
        document.getElementById('sacramento_id_feligres').value = idFeligres;

        // Cargar opciones de sacramentos
        fetch('php/obtener_sacramento.php')
            .then(res => res.json())
            .then(data => {
                const select = document.getElementById('id_sacramento');
                select.innerHTML = '<option value="">Seleccione</option>';
                data.forEach(s => {
                    select.innerHTML += `<option value="${s.id_sacramento}">${s.nombre}</option>`;
                });
            });

        new bootstrap.Modal(document.getElementById('modalAsignarSacramento')).show();
    }

    document.getElementById('formAsignarSacramento').addEventListener('submit', e => {
        e.preventDefault();
        const formData = new FormData(e.target);

        fetch('php/asignar_sacramento.php', {
            method: 'POST',
            body: formData
        })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert('Sacramento asignado correctamente');
                    bootstrap.Modal.getInstance(document.getElementById('modalAsignarSacramento')).hide();
                } else {
                    alert('Error: ' + data.message);
                }
            });
    });

    function abrirModalVer(idFeligres) {
        fetch('php/ver_sacramento.php?id=' + idFeligres)
            .then(res => res.text())
            .then(html => {
                document.getElementById('listaSacramentos').innerHTML = html;
                new bootstrap.Modal(document.getElementById('modalVerSacramentos')).show();
            });
    }


    function asignarCatequesis() {

        const modalAsignar = new bootstrap.Modal(document.getElementById('modalAsignarCatequesis'));
        const formAsignar = document.getElementById('formAsignarCatequesis');
        const selectCatequesis = document.getElementById('selectCatequesis');
        const selectCurso = document.getElementById('selectCurso');
        const divCursos = document.getElementById('divCursos');
        const idFeligresInput = document.getElementById('idFeligresAsignar');
        const fechaInscripcion = document.getElementById('fechaInscripcion');

        // Función para abrir modal y pasar ID del feligrés
        window.abrirModalAsignarCatequesis = function (feligresId) {
            idFeligresInput.value = feligresId;
            selectCatequesis.value = "";
            selectCurso.innerHTML = '<option value="">Seleccione un curso</option>';
            divCursos.classList.add('d-none');
            fechaInscripcion.value = new Date().toISOString().split('T')[0]; // fecha hoy por defecto
            modalAsignar.show();
        };

        // Cargar catequesis (podría venir por fetch desde PHP)
        async function cargarCatequesis() {
            try {
                const res = await fetch('php/obtener_catequesis.php');
                const data = await res.json();
                if (data.status) {
                    selectCatequesis.innerHTML = '<option value="">Seleccione una catequesis</option>';
                    data.catequesis.forEach(c => {
                        const option = document.createElement('option');
                        option.value = c.id_catequesis;
                        option.textContent = c.nombre;
                        selectCatequesis.appendChild(option);
                    });
                }
            } catch (err) {
                console.error('Error al cargar catequesis', err);
            }
        }

        // Al cambiar catequesis, cargar cursos relacionados
        selectCatequesis.addEventListener('change', async () => {
            const idCatequesis = selectCatequesis.value;
            if (!idCatequesis) {
                divCursos.classList.add('d-none');
                selectCurso.innerHTML = '<option value="">Seleccione un curso</option>';
                return;
            }

            try {
                const res = await fetch(`php/obtener_cursos.php?id_catequesis=${idCatequesis}`);
                const data = await res.json();
                if (data.status && data.cursos.length > 0) {
                    selectCurso.innerHTML = '<option value="">Seleccione un curso</option>';
                    data.cursos.forEach(curso => {
                        const option = document.createElement('option');
                        option.value = curso.id_curso;
                        option.textContent = `${curso.nombre} (${curso.fecha_inicio} - ${curso.fecha_fin})`;
                        selectCurso.appendChild(option);
                    });
                    divCursos.classList.remove('d-none');
                } else {
                    selectCurso.innerHTML = '<option value="">No hay cursos disponibles</option>';
                    divCursos.classList.remove('d-none');
                }
            } catch (err) {
                console.error('Error al cargar cursos', err);
                selectCurso.innerHTML = '<option value="">Error cargando cursos</option>';
                divCursos.classList.remove('d-none');
            }
        });

        // Manejar submit del formulario para guardar asignación
        formAsignar.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(formAsignar);

            try {
                const res = await fetch('php/guardar_feligres_catequesis.php', {
                    method: 'POST',
                    body: formData
                });
                const data = await res.json();

                if (data.status) {
                    alert('Asignación guardada correctamente.');
                    modalAsignar.hide();
                    // Aquí podrías recargar o actualizar la tabla, si quieres
                } else {
                    alert('Error: ' + data.message);
                }
            } catch (err) {
                console.error('Error al guardar asignación', err);
                alert('Error en la conexión, intente nuevamente.');
            }
        });

        // Inicializar cargando las catequesis
        cargarCatequesis();
    }


    document.addEventListener('DOMContentLoaded', () => {
        asignarCatequesis()
        tolstipsAcciones()
        const parroquia = document.getElementById('parroquia');
        const datosFeligres = document.getElementById('datosFeligres');
        const sacramento = document.getElementById('sacramento');
        const camposAdicionales = document.getElementById('camposAdicionales');
        const seccionPadres = document.getElementById('seccionPadres');
        const seccionPadrinos = document.getElementById('seccionPadrinos');
        const madrinaCampos = document.getElementById('madrinaCampos');
        const seccionMatrimonio = document.getElementById('seccionMatrimonio');


        // Mostrar sección de datos del feligrés cuando se selecciona una parroquia
        parroquia.addEventListener('change', () => {
            if (parroquia.value !== "") {
                datosFeligres.classList.remove('d-none');
            } else {
                datosFeligres.classList.add('d-none');
                camposAdicionales.classList.add('d-none');
            }
        });

        // Mostrar campos adicionales según sacramento
        sacramento.addEventListener("change", (e) => {
            const selectedOption = e.target.options[e.target.selectedIndex];
            const sacramentoNombre = selectedOption.getAttribute("data-sacramento");

            console.log("ID seleccionado:", sacramento.value);
            console.log("Nombre del sacramento:", sacramentoNombre);

            camposAdicionales.classList.remove("d-none");

            // Ocultar todas las secciones
            seccionPadres.classList.add("d-none");
            seccionPadrinos.classList.add("d-none");
            madrinaCampos.classList.add("d-none");
            seccionMatrimonio.classList.add("d-none");

            if (!sacramentoNombre) return;

            const nombreNormalizado = sacramentoNombre.trim().toLowerCase();

            if (["bautismo", "confirmacion", "comunion"].includes(nombreNormalizado)) {
                seccionPadres.classList.remove("d-none");
                seccionPadrinos.classList.remove("d-none");

                if (nombreNormalizado === "bautismo") {
                    madrinaCampos.classList.remove("d-none");
                }
            }

            if (nombreNormalizado === "matrimonio") {
                seccionMatrimonio.classList.remove("d-none");
            }
        });

        // Manejar el envío del formulario
        document.getElementById('formRegistroFeligres').addEventListener('submit', (e) => {
            e.preventDefault();

            const formData = new FormData();
            const sacramentoSeleccionado = sacramento.value;

            formData.append('id_parroquia', parroquia.value);
            formData.append('nombre', document.getElementById('nombre').value);
            formData.append('apellido', document.getElementById('apellido').value);
            formData.append('fecha_nacimiento', document.getElementById('fechaNacimiento').value);
            formData.append('genero', document.getElementById('genero').value);
            formData.append('direccion', document.getElementById('direccion').value);
            formData.append('estado_civil', document.getElementById('estadoCivil')?.value || 'soltero');

            if (sacramentoSeleccionado === 'matrimonio') {
                const matrimonio = {
                    telefono: document.getElementById('telefonoMatrimonio').value,
                    padrino: {
                        nombre: document.getElementById('nombrePadrinoMat').value,
                        apellido: document.getElementById('apellidoPadrinoMat').value,
                        telefono: document.getElementById('telefonoPadrinoMat').value
                    },
                    testigo: {
                        nombre: document.getElementById('nombreTestigo').value,
                        apellido: document.getElementById('apellidoTestigo').value,
                        telefono: document.getElementById('telefonoTestigo').value
                    }
                };
                formData.append('matrimonio', JSON.stringify(matrimonio));
            }

            const parientes = [];

            if (!seccionPadres.classList.contains('d-none')) {
                parientes.push({
                    tipo: 'padre',
                    nombre: document.getElementById('nombrePadre').value,
                    apellido: document.getElementById('apellidoPadre').value,
                    telefono: document.getElementById('telefonoPadre').value
                });
            }

            if (!seccionPadrinos.classList.contains('d-none')) {
                parientes.push({
                    tipo: 'padrino',
                    nombre: document.getElementById('nombrePadrino').value,
                    apellido: document.getElementById('apellidoPadrino').value,
                    telefono: document.getElementById('telefonoPadrino').value
                });

                if (!madrinaCampos.classList.contains('d-none')) {
                    parientes.push({
                        tipo: 'madrina',
                        nombre: document.getElementById('nombreMadrina').value,
                        apellido: document.getElementById('apellidoMadrina').value,
                        telefono: document.getElementById('telefonoMadrina').value
                    });
                }
            }

            if (!seccionMatrimonio.classList.contains('d-none')) {
                parientes.push({
                    tipo: 'testigo',
                    nombre: document.getElementById('nombreTestigo').value,
                    apellido: document.getElementById('apellidoTestigo').value,
                    telefono: document.getElementById('telefonoTestigo').value
                });
            }

            formData.append('parientes', JSON.stringify(parientes));
            console.log(JSON.stringify(parientes))

            formData.append('id_sacramento', sacramento.value);

            fetch('php/guardar_feligres.php', {
                method: 'POST',
                body: formData
            })
                .then(res => res.json())
                .then(data => {
                    if (data.status) {
                        alert("Feligrés registrado exitosamente.");
                        document.getElementById('formRegistroFeligres').reset();
                        datosFeligres.classList.add('d-none');
                        camposAdicionales.classList.add('d-none');
                        bootstrap.Modal.getInstance(document.getElementById('modalRegistro')).hide();
                        location.reload()
                    } else {
                        alert("Error: " + data.message);
                    }
                })
                .catch(err => {
                    console.error("Error en la solicitud:", err);
                    alert("Error de conexión. Intenta de nuevo.");
                });
        });
    });
</script>