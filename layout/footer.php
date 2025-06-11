
</div>

 
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Validación personalizada Bootstrap 5
    (() => {
        'use strict'
        const forms = document.querySelectorAll('.needs-validation')
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                form.classList.add('was-validated')
            }, false)
        })
    })()

    document.addEventListener('DOMContentLoaded', function () {
        cerrarSession();
    });
    function cerrarSession() {
        document.getElementById('cerrarSession').addEventListener('click', () => {
            if (confirm('¿Estás seguro de que deseas cerrar la sesión?')) {
                window.location.href = 'logout.php';  
                //location.reload()
            }
        });
    }


</script>

</body>

</html>