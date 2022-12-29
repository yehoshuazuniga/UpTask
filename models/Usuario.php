<?php

namespace Model;

class Usuario extends ActiveRecord
{
    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id', 'nombre', 'email', 'password', 'token', 'confirmado'];

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->password2 = $args['password2'] ?? '';
        $this->password_actual = $args['password_actual'] ?? '';
        $this->password_nuevo = $args['password_nuevo'] ?? '';
        $this->token = $args['token'] ?? '';
        $this->confirmado = $args['confirmado'] ?? 0;
    }

    //validar el login de usuarios
    public function validarLogin()
    {
        if (!$this->password) {
            self::$alertas['error'][] = 'Password es obligatorio';
        }
        if (!$this->email) {
            self::$alertas['error'][] = 'Email es obligatorio';
        }
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas['error'][] = 'El email invalido';
        }
        if (!$this->password) {
            self::$alertas['error'][] = 'Password es obligatorio';
        }
        return self::$alertas;
    }

    //validacion para cuentas
    public function validarNuevaCuenta()
    {
        if (!$this->nombre) {
            self::$alertas['error'][] = 'Nombre es obligatorio';
        }
        if (!$this->email) {
            self::$alertas['error'][] = 'Email es obligatorio';
        }

        if (!$this->password) {
            self::$alertas['error'][] = 'Password es obligatorio';
        }
        if (strlen($this->password) < 6) {
            self::$alertas['error'][] = 'El password debe contener almenos 6 caracteres';
        }

        if ($this->password !== $this->password2) {
            self::$alertas['error'][] = 'Los passwords son diferente';
        }
        return self::$alertas;
    }

    //valida un email
    public function validarEmail()
    {
        if (!$this->email) {

            self::$alertas['error'][] = 'El email es obligatorio';
        }

        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas['error'][] = 'El email invalido';
        }
        return self::$alertas;
    }

    public function comprobar_password(){
        return password_verify($this->password_actual, $this->password);
    }

    public function hashPassword()
    {
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    public function crearToken()
    {
        $this->token = uniqid();
    }

    public function validarPassword()
    {
        if (!$this->password) {
            self::$alertas['error'][] = 'Password es obligatorio';
        }
        if (strlen($this->password) < 6) {
            self::$alertas['error'][] = 'El password debe contener almenos 6 caracteres';
        }
        //  debuguear('hola');
        return self::$alertas;
    }

    public function validar_perfil()
    {

        if (!$this->nombre) {
            self::$alertas['error'][] = 'El nombre es obligatorio';
        }

        if (!$this->email) {
            self::$alertas['error'][] = 'El email es obligatorio';
        }

        return self::$alertas;
    }

    public function nuevo_password()
    {
        if (!$this->password_actual) {
            self::$alertas['error'][] = 'El  password actual no puede ir vacio';
        }
        if (!$this->password_nuevo) {
            self::$alertas['error'][] = 'El  password nuevo no puede ir vacio';
        }
        if (strlen($this->password_nuevo) < 6) {
            self::$alertas['error'][] = 'El password debe contener almenos 6 caracteres';
        }

        return self::$alertas;
    }
}
