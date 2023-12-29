<?php

namespace Controllers;

use MVC\Router;
use Model\Proyecto;
use Model\Usuario;

class DashboardController{
    
    public static function index (Router $router) {

        session_start();
        isAuth();
        
        $id = $_SESSION['id'];
        $proyectos = Proyecto::belongsTo('propietarioid', $id);
        

        $router->render('dashboard/index', [
            'titulo' => 'Proyectos',
            'proyectos' => $proyectos
        ]);
    }

    public static function crear_proyecto(Router $router) {

        session_start();
        isAuth();
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $proyecto = new Proyecto($_POST);
            // debuguear($proyecto);
            $alertas = $proyecto->validarProyecto();
            
            if(empty($alertas)){
                //Generar una URL única                
                $proyecto->url = md5(uniqid());

                //Almacenar el creador del proyecto                
                $proyecto->propietarioid = $_SESSION['id'];
                 
                //Guardar el proyecto
                $proyecto->guardar();

                //Redirecionar
                header('Location: /proyecto?id='. $proyecto->url);
                // debuguear($proyecto);

            }
            
        }

        
        
        $router->render('dashboard/crear-proyecto', [
            'titulo' => 'Crear Proyecto',
            'alertas' => $alertas
        ]);
    }

    public static function proyecto (Router $router) {

        session_start();
        isAuth();
 
        $token = $_GET['id'];
        // debuguear($_SESSION['id']);
        if(!$token) header('Location: /dashboard');
        
        //Revisar que la persona que visita el proyecto es quien creo el proyecto
        $proyecto = Proyecto::where('url',$token);
        if($proyecto->propietarioid !== $_SESSION['id']){
            header('Location: /dashboard');
        }


        $router->render('dashboard/proyecto', [
            'titulo' => $proyecto->proyecto
        ]);
    }

    public static function perfil(Router $router) {

        session_start(); 
        isAuth();
        $usuario = Usuario::find($_SESSION['id']);
        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $usuario->sincronizar($_POST);

            $alertas = $usuario->validar_perfil();
            // debuguear($alertas);
        }
        
        $router->render('dashboard/perfil', [
            'titulo' => 'Perfil',
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }
}



 