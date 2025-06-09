<?php
 
 // estas funciones se llaman desde el models/sacramentos 
postSacramentos($pdo); 
$sacramentos = getSacramentos($pdo);
?>

<main id="content">    

    <div class="container mt-4">
        <h2><i class="bi bi-bookmarks me-2"></i>Gestión de Sacramentos</h2>

        <div class="d-flex justify-content-between mb-3">
            <form class="d-flex" method="GET">
                <input class="form-control me-2" type="search" name="buscar" value="<?= htmlspecialchars($buscar) ?>"
                    placeholder="Buscar sacramento...">
                <button class="btn btn-outline-primary" type="submit"><i class="bi bi-search"></i></button>
            </form>
             
        </div>

        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                   
                </tr>
            </thead>
            <tbody>
                <?php foreach ($sacramentos  as $s): ?>
                    <tr>
                        <td><?= $s['id_sacramento'] ?></td>
                        <td><?= htmlspecialchars($s['nombre']) ?></td>
                         
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>

    <!-- Modal Registro -->
    <div class="modal fade" id="modalRegistro" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form action="../php/guardar_sacramento.php" method="POST" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-plus-circle me-1"></i>Registrar Sacramento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" name="nombre" id="nombre" class="form-control" required maxlength="50">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Registrar</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modales de edición -->
    <?php foreach ($sacramentos as $s): ?>
        <div class="modal fade" id="modalEditar<?= $s['id_sacramento'] ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <form action="../php/editar_sacramento.php" method="POST" class="modal-content">
                    <input type="hidden" name="id_sacramento" value="<?= $s['id_sacramento'] ?>">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="bi bi-pencil-square me-1"></i>Editar Sacramento</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <label for="nombre<?= $s['id_sacramento'] ?>" class="form-label">Nombre</label>
                        <input type="text" name="nombre" id="nombre<?= $s['id_sacramento'] ?>" class="form-control"
                               value="<?= htmlspecialchars($s['nombre']) ?>" required maxlength="50">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    <?php endforeach; ?>
</main>
