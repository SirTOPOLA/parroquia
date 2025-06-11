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
$sacramentos = $pdo->query("SELECT * FROM sacramentos")->fetchAll(PDO::FETCH_ASSOC);

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
                    <th>Teléfono</th>
                    <th>Estado Civil</th>
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
                            <td><?= htmlspecialchars($f['telefono']) ?></td>
                            <td><?= ucfirst($f['estado_civil']) ?></td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                    data-bs-target="#modalEditar<?= $f['id_feligres'] ?>">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <a href="../php/eliminar_feligres.php?id=<?= $f['id_feligres'] ?>" class="btn btn-sm btn-danger"
                                    onclick="return confirm('¿Eliminar este feligrés?')">
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
                                <option value="<?= $p['id_parroquia'] ?>"><?= htmlspecialchars($p['nombre']) ?></option>
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
                                    <option value="Masculino">Masculino</option>
                                    <option value="Femenino">Femenino</option>
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

<!-- JavaScript del Modal -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const parroquia = document.getElementById('parroquia');
        const datosFeligres = document.getElementById('datosFeligres');
        const sacramento = document.getElementById('sacramento');
        const camposAdicionales = document.getElementById('camposAdicionales');
        const seccionPadres = document.getElementById('seccionPadres');
        const seccionPadrinos = document.getElementById('seccionPadrinos');
        const madrinaCampos = document.getElementById('madrinaCampos');
        const seccionMatrimonio = document.getElementById('seccionMatrimonio');

        parroquia.addEventListener('change', () => {
            if (parroquia.value !== "") {
                datosFeligres.classList.remove('d-none');
            } else {
                datosFeligres.classList.add('d-none');
                camposAdicionales.classList.add('d-none');
            }
        });

        sacramento.addEventListener('change', () => {
            camposAdicionales.classList.remove('d-none');

            // Reset secciones
            seccionPadres.classList.add('d-none');
            seccionPadrinos.classList.add('d-none');
            madrinaCampos.classList.add('d-none');
            seccionMatrimonio.classList.add('d-none');
            const seleccionado = this.options[this.selectedIndex];
            const tipo = seleccionado.dataset.sacramento;
             

            if (['bautismo', 'comunion', 'confirmacion'].includes(tipo)) {
                seccionPadres.classList.remove('d-none');
                seccionPadrinos.classList.remove('d-none');
            }

            if (tipo === 'bautismo') {
                madrinaCampos.classList.remove('d-none');
            }

            if (tipo === 'matrimonio') {
                seccionMatrimonio.classList.remove('d-none');
            }
        });

        // Manejar el envío del formulario
        document.getElementById('formRegistroFeligres').addEventListener('submit', (e) => {
            e.preventDefault();

            const formData = new FormData();
            const sacramentoSeleccionado = sacramento.value;

            // Datos básicos
            formData.append('id_parroquia', parroquia.value);
            formData.append('nombre', document.getElementById('nombre').value);
            formData.append('apellido', document.getElementById('apellido').value);
            formData.append('fecha_nacimiento', document.getElementById('fechaNacimiento').value);
            formData.append('genero', document.getElementById('genero').value);
            formData.append('direccion', document.getElementById('direccion').value);

            // Estado civil (solo si es matrimonio)
            formData.append('estado_civil', document.getElementById('estadoCivil')?.value || 'soltero');

            // Matrimonio
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

            // Parientes (padres, padrinos, madrina, testigo)
            const parientes = [];

            // Padres
            if (!seccionPadres.classList.contains('d-none')) {
                parientes.push({
                    tipo: 'padre',
                    nombre: document.getElementById('nombrePadre').value,
                    apellido: document.getElementById('apellidoPadre').value,
                    telefono: document.getElementById('telefonoPadre').value
                });
            }

            // Padrinos
            if (!seccionPadrinos.classList.contains('d-none')) {
                parientes.push({
                    tipo: 'padrino',
                    nombre: document.getElementById('nombrePadrino').value,
                    apellido: document.getElementById('apellidoPadrino').value,
                    telefono: document.getElementById('telefonoPadrino').value
                });

                // Madrina (solo bautismo)
                if (!madrinaCampos.classList.contains('d-none')) {
                    parientes.push({
                        tipo: 'madrina',
                        nombre: document.getElementById('nombreMadrina').value,
                        apellido: document.getElementById('apellidoMadrina').value,
                        telefono: document.getElementById('telefonoMadrina').value
                    });
                }
            }

            // Testigo (solo matrimonio)
            if (!seccionMatrimonio.classList.contains('d-none')) {
                parientes.push({
                    tipo: 'testigo',
                    nombre: document.getElementById('nombreTestigo').value,
                    apellido: document.getElementById('apellidoTestigo').value,
                    telefono: document.getElementById('telefonoTestigo').value
                });
            }

            formData.append('parientes', JSON.stringify(parientes));

            // Sacramento
            formData.append('sacramentos', JSON.stringify([
                { tipo: sacramentoSeleccionado }
            ]));

            // Enviar
            fetch('php/guardar_feligres.php', {
                method: 'POST',
                body: formData
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        alert("Feligrés registrado exitosamente.");
                        document.getElementById('formRegistroFeligres').reset();
                        datosFeligres.classList.add('d-none');
                        camposAdicionales.classList.add('d-none');
                        bootstrap.Modal.getInstance(document.getElementById('modalRegistro')).hide();
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