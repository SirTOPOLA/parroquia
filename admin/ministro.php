<?php include '../includes/header.php'; ?>
<?php include '../includes/sidebar.php'; ?>
<main class="content">
<div class="container-fluid px-4">
     <div
      class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
       
      <h2 class="mt-4"><i class="bi bi-person-badge"></i> Gestión de Ministros</h2>

      <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center gap-2 w-100 w-md-auto">
        <input type="text" id="buscar" class="form-control" placeholder="Buscar por nombre ..">

        <!-- <button class="btn btn-primary" data-bs-toggle="modal" id="formRegistrar" data-bs-target="#modalAgregar">
          <i class="bi bi-person-plus-fill me-1"></i> Nuevo
        </button> -->
        <!-- Botón para abrir el modal -->
<button class="btn btn-primary" data-bs-toggle="modal" id="btnAbrirRegistro" data-bs-target="#modalRegistro">
  <i class="bi bi-person-plus-fill me-1"></i> Nuevo
</button>

      </div>
    </div>


    
    <div class="card-body table-responsive">
        <table class="table table-bordered table-hover text-center align-middle">
            <thead class="table-linght">
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Tipo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="tablaMinistros">
                <!-- Contenido dinámico con JS -->
            </tbody>
        </table>
    </div>

</div>
</main>
<!-- Modal Registro -->
<div class="modal fade" id="modalRegistro" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="formRegistrar" class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="bi bi-plus-circle"></i> Registrar Ministro</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Nombre:</label>
                    <input type="text" name="nombre" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tipo:</label>
                    <select name="tipo" class="form-select" required>
                        <option value="">Seleccione</option>
                        <option value="sacerdote">Sacerdote</option>
                        <option value="obispo">Obispo</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button class="btn btn-success" type="submit">Guardar</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edición -->
<div class="modal fade" id="modalEditar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="formEditar" class="modal-content">
            <input type="hidden" name="id" id="edit_id">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="bi bi-pencil-square"></i> Editar Ministro</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Nombre:</label>
                    <input type="text" name="nombre" id="edit_nombre" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tipo:</label>
                    <select name="tipo" id="edit_tipo" class="form-select" required>
                        <option value="">Seleccione</option>
                        <option value="sacerdote">Sacerdote</option>
                        <option value="obispo">Obispo</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button class="btn btn-primary" type="submit">Actualizar</button>
            </div>
        </form>
    </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    cargarMinistros();

    const formRegistrar = document.getElementById('formRegistrar');
    const formEditar = document.getElementById('formEditar');

    formRegistrar.addEventListener('submit', async e => {
      e.preventDefault();
      const data = new FormData(formRegistrar);

      try {
        const res = await fetch('../php/guardar_ministro.php', {
          method: 'POST',
          body: data
        });
        const json = await res.json();
        alert(json.message);

        if (json.status) {
          formRegistrar.reset();
          bootstrap.Modal.getInstance(document.getElementById('modalRegistro')).hide();
          cargarMinistros();
        }
      } catch (error) {
        console.error('Error al registrar:', error);
        alert('Error inesperado al registrar el ministro.');
      }
    });

    formEditar.addEventListener('submit', async e => {
      e.preventDefault();
      const data = new FormData(formEditar);

      try {
        const res = await fetch('../php/editar_ministro.php', {
          method: 'POST',
          body: data
        });
        const json = await res.json();
        alert(json.message);

        if (json.status) {
          bootstrap.Modal.getInstance(document.getElementById('modalEditar')).hide();
          cargarMinistros();
        }
      } catch (error) {
        console.error('Error al editar:', error);
        alert('Error inesperado al editar el ministro.');
      }
    });
  });

 async function cargarMinistros() {
    try {
      const res = await fetch('../php/listar_ministro.php');
      const html = await res.text();
      document.getElementById('tablaMinistros').innerHTML = html; 
    } catch (err) {
      document.getElementById('tablaMinistros').innerHTML = '<p class="text-danger">Error al cargar ministerios.</p>';
      console.error(err);
    }
  }

 
  function mostrarEditar(id, nombre, tipo) {
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_nombre').value = nombre;
    document.getElementById('edit_tipo').value = tipo;
    new bootstrap.Modal(document.getElementById('modalEditar')).show();
  }

  async function eliminarMinistro(id) {
    if (!confirm('¿Desea eliminar este ministro?')) return;

    const data = new FormData();
    data.append('id', id);

    try {
      const res = await fetch('../php/eliminar_ministro.php', {
        method: 'POST',
        body: data
      });
      const json = await res.json();
      alert(json.message);
      if (json.status) cargarMinistros();
    } catch (error) {
      console.error('Error al eliminar:', error);
      alert('Error inesperado al eliminar el ministro.');
    }
  }
</script>

 <?php include '../includes/footer.php'; ?>