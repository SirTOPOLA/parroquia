<?php include '../includes/header.php'; ?>
<?php include '../includes/sidebar.php'; ?>
<?php
$buscar = $_GET['buscar'] ?? '';

// Consulta de usuarios con nombre completo de la persona asociada
$sql = "SELECT u.*, p.nombres, p.apellidos 
        FROM usuarios u 
        JOIN persona p ON u.persona_id = p.id 
        WHERE CONCAT(p.nombres, ' ', p.apellidos) LIKE :buscar 
        ORDER BY u.id DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute(['buscar' => "%$buscar%"]);
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="content">
    <?php if (isset($_GET['mensaje'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= ucfirst($_GET['mensaje']) ?> correctamente.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="container mt-4">
        <h2>Gestión de Usuarios</h2>

        <div class="d-flex justify-content-between mb-3">
            <form class="d-flex" method="GET">
                <input class="form-control me-2" type="search" name="buscar" value="<?= htmlspecialchars($buscar) ?>"
                    placeholder="Buscar usuario...">
                <button class="btn btn-outline-primary" type="submit">Buscar</button>
            </form>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalRegistro">+ Nuevo Usuario</button>
        </div>

        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Nombre Persona</th>
                    <th>Usuario</th>
                    <th>Rol</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $u): ?>
                    <tr>
                        <td><?= $u['id'] ?></td>
                        <td><?= $u['nombres'] . ' ' . $u['apellidos'] ?></td>
                        <td><?= $u['usuario'] ?></td>
                        <td><?= ucfirst($u['rol']) ?></td>
                        <td>
<td>
  <button class="btn toggle-estado btn-sm d-flex align-items-center gap-2 <?= $u['estado'] ? 'btn-outline-success' : 'btn-outline-danger' ?>"
          data-id="<?= $u['id'] ?>" title="Activar / Desactivar">
    <i class="bi <?= $u['estado'] ? 'bi-toggle-on' : 'bi-toggle-off' ?> fs-4"></i>
    <span class="estado-text"><?= $u['estado'] ? 'ACTIVO' : 'INACTIVO' ?></span>
  </button>
</td>


                        <td>
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                data-bs-target="#modalEditar<?= $u['id'] ?>">Editar</button>
                            <a href="../php/eliminar_usuario.php?id=<?= $u['id'] ?>" class="btn btn-sm btn-danger"
                                onclick="return confirm('¿Eliminar este usuario?')">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>

    <!-- Modal de Registro -->
    <div class="modal fade" id="modalRegistro" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form action="../php/guardar_usuario.php" method="POST" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Registrar Usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body row g-3">
                    <?php 
                    $modo = 'registrar';
                    include '../components/form_usuario.php'; 
                    ?>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Registrar</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modales de Edición (fuera de la tabla) -->
    <?php foreach ($usuarios as $u): ?>
        <div class="modal fade" id="modalEditar<?= $u['id'] ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <form action="../php/editar_usuario.php" method="POST" class="modal-content">
                    <input type="hidden" name="id" value="<?= $u['id'] ?>">
                    <div class="modal-header">
                        <h5 class="modal-title">Editar Usuario</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body row g-3">
                        <?php 
                        $modo = 'editar'; 
                        $datosUsuario = $u;
                        include '../components/form_usuario.php'; 
                        ?>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Guardar cambios</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    <?php endforeach; ?>
</main>

<script>
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.toggle-estado').forEach(btn => {
    btn.addEventListener('click', () => {
      const id = btn.dataset.id;
      const currentState = btn.querySelector('.estado-text').textContent.trim();

      const mensaje = currentState === 'ACTIVO'
        ? '¿Estás seguro que deseas DESACTIVAR este usuario?'
        : '¿Estás seguro que deseas ACTIVAR este usuario?';

      if (!confirm(mensaje)) return; // cancelado por el usuario

      fetch('../php/cambiar_estado_usuario.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: 'id=' + encodeURIComponent(id)
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          const icon = btn.querySelector('i');
          const text = btn.querySelector('.estado-text');

          if (data.estado == 1) {
            btn.classList.remove('btn-outline-danger');
            btn.classList.add('btn-outline-success');
            icon.classList.remove('bi-toggle-off');
            icon.classList.add('bi-toggle-on');
            text.textContent = 'ACTIVO';
          } else {
            btn.classList.remove('btn-outline-success');
            btn.classList.add('btn-outline-danger');
            icon.classList.remove('bi-toggle-on');
            icon.classList.add('bi-toggle-off');
            text.textContent = 'INACTIVO';
          }
        } else {
          alert('Error: ' + data.error);
        }
      })
      .catch(err => {
        console.error('Error AJAX:', err);
        alert('Ocurrió un error al cambiar el estado.');
      });
    });
  });
});
</script>


<?php include '../includes/footer.php'; ?>
