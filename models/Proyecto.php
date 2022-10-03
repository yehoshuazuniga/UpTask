<?php
namespace Model;

use Model\ActiveRecord;

class Proyecto extends ActiveRecord{
    protected static $tabla ='proyectos';
    protected static $columnasDB= ['id', 'proyecto', 'url', 'propietarioId'];

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->proyecto = $args['proyecto'] ?? null;
        $this->url = $args['url'] ?? null;
        $this->propietarioId = $args['propietarioId'] ?? null;
    }

    public function validaProyecto(){
        if(!$this->proyecto){
            self::$alertas['error'][]='El nombre del proyecto es obligatorio';
        }
    }
}