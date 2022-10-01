<?php

namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

class LoginController
{
    public static function login(Router $router)
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        }

        //render a la vista
        $router->render('auth/login', [
            'titulo' => 'Iniciar Sesión'
        ]);
    }
    public static function logout(Router $router)
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        }

        $router->render('auth/crear', [
            'titulo' => 'Crear cuenta de Up Task'
        ]);
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
                }
            } else {
                //crear nuevo usuario
                $usuario->hashPassword();
                //eliminar password 2
                unset($usuario->password2);
                //generar un token
                $usuario->crearToken();
                //hashear password
                $resultado = $usuario->guardar();

                $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                debuguear($email);
                if ($resultado) {
                    header('Location: /mensaje');
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
        echo 'hola desde olvide';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        }

        $router->render('auth/olvide', [
            'titulo' => 'Olvide mi Password'
        ]);
    }
    public static function reestablecer(Router $router)
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        }
        $router->render('auth/reestablecer', [
            'titulo' => 'Reestablecer password'
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
        } else{
            //confirmar cuenta
            $usuario->confirmado=1;
            $usuario->token=null;
            unset($usuario->password2);
            debuguear($usuario);
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
