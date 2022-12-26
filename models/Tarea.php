<?php

namespace Model;

class Tarea extends ActiveRecord{
    protected static $tabla ="tareas";
    protected static $columnasDB = ['id', 'nombre', 'estado', 'proyectoId'];

    public function __construct($arg=[])
    {
        $this ->id= $arg['id']??null;
        $this ->nombre= $arg['nombre']??'';
        $this ->estado= $arg['estado']??0;
        $this ->proyectoId= $arg['proyectoId']??'';        
    }

    //API para las tareas
    


}


?>