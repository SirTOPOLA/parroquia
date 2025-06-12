<?php


$buscar = $_GET['buscar'] ?? '';

// Consulta para listar catequesis con búsqueda simple
$sql = "SELECT * FROM catequesis WHERE nombre LIKE :buscar ORDER BY id_catequesis DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute(['buscar' => "%$buscar%"]);
$catequesis = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Función para contar cursos por catequesis
function contarCursos($pdo, $id_catequesis)
{
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM cursos WHERE id_catequesis = ?");
    $stmt->execute([$id_catequesis]);
    return $stmt->fetchColumn();
}

// Función para contar feligreses asociados por curso
function contarFeligresesCurso($pdo, $id_curso)
{
    $stmt = $pdo->prepare("
        SELECT COUNT(*) 
        FROM feligres_catequesis fc
        INNER JOIN cursos c ON fc.id_catequesis = c.id_catequesis
        WHERE c.id_curso = ?
    ");
    $stmt->execute([$id_curso]);
    return $stmt->fetchColumn();
}

// Función para contar parientes asociados por curso
function contarParientesCurso($pdo, $id_curso)
{
    $stmt = $pdo->prepare("
        SELECT COUNT(*) 
        FROM pariente_catequesis pc
        INNER JOIN cursos c ON pc.id_catequesis = c.id_catequesis
        WHERE c.id_curso = ?
    ");
    $stmt->execute([$id_curso]);
    return $stmt->fetchColumn();
}

// Obtener cursos asociados a esta catequesis
$stmtCursos = $pdo->prepare("SELECT * FROM cursos WHERE id_catequesis = ?");
$stmtCursos->execute([$c['id_catequesis']]);
$cursos = $stmtCursos->fetchAll(PDO::FETCH_ASSOC);

// Contar feligreses y parientes totales de esta catequesis sumando cursos
$totalFeligreses = 0;
$totalParientes = 0;
foreach ($cursos as $curso) {
    // contar feligreses y parientes para cada curso
    $totalFeligreses += contarFeligresesCurso($pdo, $curso['id_curso']);
    $totalParientes += contarParientesCurso($pdo, $curso['id_curso']);
}

?>

<main id="content">
    <div class="container mt-4">
        <h2><i class="bi bi-book me-2"></i>Gestión de Catequesis</h2>

        <div class="d-flex justify-content-between mb-3">
            <form class="d-flex" method="GET">
                <input class="form-control me-2" type="search" name="buscar" value="<?= htmlspecialchars($buscar) ?>"
                    placeholder="Buscar catequesis...">
                <button class="btn btn-outline-primary" type="submit"><i class="bi bi-search"></i></button>
            </form>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalNuevaCatequesis">
                <i class="bi bi-plus-lg me-1"></i>Nueva Catequesis
            </button>

        </div>

        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th># Cursos</th>
                    <th># Feligreses</th>
                    <th># Parientes</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($catequesis as $c): ?>

                    <tr>
                        <td><?= $c['id_catequesis'] ?></td>
                        <td><?= htmlspecialchars($c['nombre']) ?></td>
                        <td><?= htmlspecialchars($c['descripcion']) ?></td>
                        <td><?= count($cursos) ?></td>
                        <td><?= $totalFeligreses ?></td>
                        <td><?= $totalParientes ?></td>
                        <td class="text-center">
                            <a href="catequesis_editar.php?id=<?= $c['id_catequesis'] ?>" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil-square"></i> Editar
                            </a>
                            <a href="catequesis_eliminar.php?id=<?= $c['id_catequesis'] ?>" class="btn btn-sm btn-danger"
                                onclick="return confirm('¿Eliminar esta catequesis?')">
                                <i class="bi bi-trash"></i> Eliminar
                            </a>
                            <!-- Botón de prueba -->
                            <button class="btn btn-info" onclick="mostrarDetalleCatequesis(<?= $c['id_catequesis'] ?>)">
                                <i class="bi bi-aye"></i> Ver Catequesis #3
                            </button>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
</main>


<div class="modal fade" id="modalNuevaCatequesis" tabindex="-1" aria-labelledby="modalNuevaCatequesisLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content shadow rounded-4">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="modalNuevaCatequesisLabel">
                    <i class="bi bi-bookmark-plus-fill me-2"></i>Registrar Nueva Catequesis
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <form id="formNuevaCatequesis">
                <div class="modal-body">

                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre de la Catequesis <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>

                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="2"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">¿Qué deseas hacer después?</label>
                        <select class="form-select" name="accion_post" id="accion_post">
                            <option value="nada">Solo registrar catequesis</option>
                            <option value="curso">Registrar y crear curso</option>
                            <option value="catequista">Registrar y asignar catequista</option>
                        </select>
                    </div>

                    <!-- Campos dinámicos: crear curso -->
                    <div id="campoCurso" class="d-none border-start border-success ps-3 mb-3">
                        <h6 class="text-success mt-3">Información del Curso</h6>
                        <div class="mb-2">
                            <label for="nombre_curso" class="form-label">Nombre del Curso</label>
                            <input type="text" class="form-control" id="nombre_curso" name="nombre_curso">
                        </div>
                        <div class="row g-2">
                            <div class="col-md-6">
                                <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
                                <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio">
                            </div>
                            <div class="col-md-6">
                                <label for="fecha_fin" class="form-label">Fecha de Fin</label>
                                <input type="date" class="form-control" id="fecha_fin" name="fecha_fin">
                            </div>
                        </div>
                    </div>

                    <!-- Campos dinámicos: asignar catequista -->
                    <div id="campoCatequista" class="d-none border-start border-info ps-3 mb-3">
                        <h6 class="text-info mt-3">Asignar Catequista</h6>
                        <div class="mb-2">
                            <label for="id_catequista" class="form-label">Selecciona un Catequista</label>
                            <select class="form-select" id="id_catequista" name="id_catequista">
                                <option value="">-- Seleccionar --</option>
                                <?php
                                // Opcional: cargar catequistas desde PHP (puedes hacerlo también con fetch)
                                $stmt = $pdo->query("SELECT id_catequista, nombre FROM catequistas");
                                while ($c = $stmt->fetch(PDO::FETCH_ASSOC)):
                                    ?>
                                    <option value="<?= $c['id_catequista'] ?>"><?= htmlspecialchars($c['nombre']) ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle"></i> Guardar Catequesis
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('accion_post').addEventListener('change', function () {
        const valor = this.value;

        document.getElementById('campoCurso').classList.toggle('d-none', valor !== 'curso');
        document.getElementById('campoCatequista').classList.toggle('d-none', valor !== 'catequista');
    });

    document.getElementById('formNuevaCatequesis').addEventListener('submit', async function (e) {
        e.preventDefault();

        const form = this;
        const formData = new FormData(form);
        const accion = formData.get('accion_post');

        // Validaciones básicas antes de enviar
        const nombre = formData.get('nombre')?.trim();
        if (!nombre) {
            alert("El nombre de la catequesis es obligatorio.");
            return;
        }

        if (accion === 'curso') {
            const nombreCurso = formData.get('nombre_curso')?.trim();
            const fechaInicio = formData.get('fecha_inicio');
            const fechaFin = formData.get('fecha_fin');

            if (!nombreCurso || !fechaInicio || !fechaFin) {
                alert("Completa todos los campos del curso.");
                return;
            }

            if (fechaFin < fechaInicio) {
                alert("La fecha de fin no puede ser anterior a la de inicio.");
                return;
            }
        }

        if (accion === 'catequista') {
            const idCatequista = formData.get('id_catequista');
            if (!idCatequista) {
                alert("Selecciona un catequista.");
                return;
            }
        }

        try {
            const response = await fetch('guardar_catequesis.php', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (response.ok) {
                alert("Catequesis registrada correctamente.");
                form.reset();
                document.getElementById('campoCurso').classList.add('d-none');
                document.getElementById('campoCatequista').classList.add('d-none');
                const modal = bootstrap.Modal.getInstance(document.getElementById('modalNuevaCatequesis'));
                modal.hide();
                // Puedes recargar tabla o actualizar vista si usas DataTable, etc.
            } else {
                alert(data.error || "Ocurrió un error al registrar la catequesis.");
            }
        } catch (err) {
            console.error("Error al enviar:", err);
            alert("Error en el envío. Intenta nuevamente.");
        }
    });



    function mostrarDetalleCatequesis(id) {
        fetch('api/modalCatequesis.php?id=' + id)
            .then(res => res.text())
            .then(html => {
                const modalContainer = document.createElement('div');
                modalContainer.innerHTML = html;
                document.body.appendChild(modalContainer);

                const modal = new bootstrap.Modal(document.getElementById('modalCatequesisDetalle'));
                modal.show();

                // Opcional: remover el modal del DOM al cerrarse
                document.getElementById('modalCatequesisDetalle').addEventListener('hidden.bs.modal', () => {
                    modalContainer.remove();
                });
            })
            .catch(err => alert("Error al cargar los datos: " + err));
    }
</script>