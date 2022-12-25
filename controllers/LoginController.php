<?php

namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

class LoginController
{
    public static function login(Router $router)
    {
        $alertas = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new Usuario($_POST);

            $alertas = $auth->validarLogin();
            if (empty($alertas)) {
                // verificar usuario 
                $usuario = Usuario::where('email', $auth->email);

                if (!$usuario || !$usuario->confirmado) {
                    Usuario::setAlerta('error', 'El usuario no existe o no esta confirmado');
                } else {
                    // el usuario existe
                    if (password_verify($_POST['password'], $usuario->password)) {
                        // iniciar sesion del usuario
                        session_start();

                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['login'] = true;
                        $_SESSION['nombre'] = $usuario->nombre;
                        $_SESSION['email'] = $usuario->email;

                        //redireccionar 
                        header('Location: /dashboard');
                        //  debuguear($_SESSION);
                    } else {
                        Usuario::setAlerta('error', 'El passsword es incorrecto');
                    }
                }
                // debuguear($usuario);
            }

            // debuguear($auth);
        }
        $alertas = Usuario::getAlertas();
        //render a la vista
        $router->render('auth/login', [
            'titulo' => 'Iniciar Sesión',
            'alertas' => $alertas
        ]);
    }
    public static function logout(Router $router)
    {

        session_start();
        $_SESSION = [];
        header('Location: /');
    }
    public static function crear(Router $router)
    {
        $alertas = [];
        $usuario = new Usuario();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta();
            if (empty($alertas)) {
                $existeUsuario = Usuario::where('email', $usuario->email);
                if ($existeUsuario) {
                    Usuario::setAlerta('error', 'El usuario ya existe');
                    $alertas = Usuario::getAlertas();
                } else {
                    // 
                    //crear nuevo usuario
                    $usuario->hashPassword();
                    //eliminar password 2
                    unset($usuario->password2);
                    //generar un token
                    $usuario->crearToken();
                    //hashear password
                    $resultado = $usuario->guardar();
                    //debuguear($usuario);
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    // debuguear($email);
                    $email->enviarConfirmacion();
                    if ($resultado) {
                        header('Location: /mensaje');
                    }
                }
            }
        }

        $router->render('auth/crear', [
            'titulo' => 'Crear tu ceunta',
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }
    public static function olvide(Router $router)
    {
        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = new Usuario($_POST);
            $alertas = $usuario->validarEmail();

            if (empty($alertas)) {

                //buscar email
                $usuario = Usuario::where('email', $usuario->email);
                //     debuguear($usuario);
                if ($usuario && $usuario->confirmado) {
                    //generar un nuevo token
                    $usuario->crearToken();
                    unset($usuario->password2);
                    //actualizar el usuario
                    $usuario->guardar();
                    //enviar el email
                    $email = new Email($usuario->email, $usuario->nombre,  $usuario->token);
                    $email->enviarInstrucciones();
                    //imprimir alerta
                    Usuario::setAlerta('exito', 'Enviamos las Instrucciones a tu email');
                    //   debuguear($usuario);
                } else {
                    Usuario::setAlerta('error', 'El  usuario no existe o no esta confirmado');
                }
            }
        }
        $alertas = Usuario::getAlertas();

        //muestra la vista
        $router->render('auth/olvide', [
            'titulo' => 'Olvide mi Password',
            'alertas' => $alertas
        ]);
    }
    public static function reestablecer(Router $router)
    {
        $token = s($_GET['token']);
        $mostrar = true;

        if (!$token) header('Location: /');

        //iedntificar el usuario con ese token

        $usuario = Usuario::where('token', $token);
        if (empty($usuario)) {
            Usuario::setAlerta('error', 'Usuario no válido');
            $mostrar = false;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //añadir el nuevo password
            $usuario->sincronizar($_POST);

            //validar el password
            $alertas =  $usuario->validarPassword();
            if (empty($alertas)) {
                //hash password
                $usuario->hashPassword();
                //eliminar el token_get_all
                $usuario->token = null;
                // debuguear($usuario);
                //guardar el usuario en la bd
                $resultado = $usuario->guardar();
                // debuguear(($usuario));

                //redireccionar
                if ($resultado) {
                    header('Location: /');
                }
            }
            //debuguear($usuario);
        }

        $alertas = Usuario::getAlertas();
        $router->render('auth/reestablecer', [
            'titulo' => 'Reestablecer password',
            'alertas' => $alertas,
            'mostrar' => $mostrar
        ]);
    }
    public static function mensaje(Router $router)
    {


        $router->render('auth/mensaje', [
            'titulo' => 'Cuenta creada exitosamente'
        ]);
    }
    public static function confirmar(Router $router)
    {
        $token = s($_GET['token']);

        if (!$token) header('location: /');

        //encontarr usuario con el roken

        $usuario = Usuario::where('token', $token);

        if (empty($usuario)) {
            //No se encontro
            Usuario::setAlerta('error', 'Token no valido');
        } else {
            //confirmar cuenta
            $usuario->confirmado = 1;
            $usuario->token = null;
            unset($usuario->password2);
            //  debuguear($usuario);
            $usuario->guardar();
            Usuario::setAlerta('exito', 'Cuenta comprobada correctamente');
        }

        $alertas = Usuario::getAlertas();
        $router->render('auth/confirmar', [
            'titulo' => 'Confitma tu cuenta UpTaskng',
            'alertas' => $alertas
        ]);
    }
}
