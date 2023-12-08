<?php

namespace Model;

use Model\ActiveRecord;

class Proyecto extends ActiveRecord {
    protected static $tabla = 'proyectos';
    protected static $columnasDB = [ 'id', 'proyecto', 'url', 'propietarioid'];

    public static $id;
    public static $proyecto;
    public static $url;
    public static $propietarioid;

    public function __construct($args = []) {

        $this->id = $args['id'] ?? null;
        $this->proyecto = $args['proyecto'] ?? '';
        $this->url = $args['url'] ?? '';
        $this->propietarioid = $args['propietarioid'] ?? '';
    }

    public function validarProyecto() {
        if(!$this->proyecto){
            self::$alertas['error'][] = 'El nombre del proyecto es obligatorio';
        }
        return self::$alertas;
    }
}