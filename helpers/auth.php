<?php
// Verificar si la sesión ya está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
 

/**
 * Login específico para Internos.
 * Busca en la tabla `usuarios` usando un campo `usuario` y `password_hash`.
 */
function login($pdo, $usuario, $contrasena)
{
    // Consulta segura con sentencia preparada
    $stmt = $pdo->prepare("SELECT id, 
                                nombre, 
                                dni, 
                                usuario AS username, 
                                contrasena AS password, 
                                rol, 
                                estado AS activo
                           FROM usuarios 
                           WHERE usuario = ?");
    $stmt->execute([$usuario]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        if (password_verify($contrasena, $user['password'])) {
            if ($user['activo'] == 1) {
                // Login correcto y cuenta activa
                $_SESSION['usuario'] = [
                    'id'      => $user['id'],
                    'nombre'  => $user['nombre'],
                    'dip'     => $user['dni'],
                    'usuario' => $user['username'],
                    'rol'     => $user['rol']
                ];
                $_SESSION['alerta'] = ['tipo' => 'success', 'mensaje' => 'Inicio de sesión exitoso.'];
                return true;
            } else {
                // Usuario correcto pero cuenta inactiva
                $_SESSION['alerta'] = ['tipo' => 'warning', 'mensaje' => 'Tu cuenta está inactiva. Contacta al administrador.'];
                return false;
            }
        } else {
            // Contraseña incorrecta
            $_SESSION['alerta'] = ['tipo' => 'danger', 'mensaje' => 'Usuario o contraseña incorrectos.'];
            return false;
        }
    } else {
        // Usuario no encontrado
        $_SESSION['alerta'] = ['tipo' => 'danger', 'mensaje' => 'Usuario o contraseña incorrectos.'];
        return false;
    }
}



/**
 * Destruccion del login.
 *    .
 */
function logout()
{
    session_unset();
    session_destroy();
    
    header('Location: index.php?vista=login');
    exit;
}


