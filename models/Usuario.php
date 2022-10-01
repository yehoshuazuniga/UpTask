<?php

namespace Model;
class Usuario extends ActiveRecord{
    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id', 'nombre', 'email', 'password', 'token', 'confirmado'];

    public function __construct($args =[])
    {
        $this->id=$args['id'] ?? null;
        $this->nombre=$args['nombre'] ?? '';
        $this->email=$args['email'] ?? '';
        $this->password=$args['password'] ?? '';
        $this->password2=$args['password2'] ?? '';
        $this->token=$args['token'] ?? '';
        $this->confirmado=$args['confirmado'] ?? 0;
    }

    //validacion para cuentas
    public function validarNuevaCuenta(){
        if(!$this->nombre){
            self::$alertas['error'][]= 'Nombre es obligatorio';
        }
        if(!$this->email){
            self::$alertas['error'][]= 'Email es obligatorio';
        }
        if(!$this->password){
            self::$alertas['error'][]= 'Password es obligatorio';
        }
        if(!$this->password){
            self::$alertas['error'][]= 'Password es obligatorio';
        }
        if(strlen($this->password)){
            self::$alertas['error'][]= 'El password debe contener almenos 6 caracteres';
        }

        if($this->password !== $this->password2){
            self::$alertas['error'][]= 'Los passwords son diferente';
        }
        return self::$alertas;
    }

    public function hashPassword(){
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    public function crearToken(){
        $this->token = uniqid();
    }
}