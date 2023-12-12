<?php

namespace Model;
use Model\ActiveRecord;

class Tarea extends ActiveRecord {
    protected static $tabla = 'tareas';
    protected static $columnasDB = ['id','nombre','estado','proyectoid'];

    public $id;
    public $nombre;
    public $estado;
    public $proyectoid;
    
    public function __construct($args = []){
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->estado = $args['esatdo'] ?? 0;
        $this->proyectoid = $args['proyectoId'] ?? '';

    }
}