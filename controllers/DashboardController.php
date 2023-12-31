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
            
            if(empty($alertas)){
                
                $existeUsuario  = Usuario::where('email', $usuario->email);
                
                if($existeUsuario && $existeUsuario->id !== $usuario->id){
                    //Mensaje de error
                    Usuario::setAlerta('error', 'Correo ya registrado');
                    $alertas = $usuario->getAlertas();
                }else{
                    //Guardar el registro
                    $usuario->guardar();
                    Usuario::setAlerta('exito', 'Guardado Correctamente');
                }
                
                // Guardar el usuario
                $alertas = $usuario->getAlertas();
                //Asignar el nombre a la barra
                $_SESSION['nombre']  = $usuario->nombre;
            }
        }
        
        $router->render('dashboard/perfil', [
            'titulo' => 'Perfil',
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }

    public static function cambiar_password(Router $router){

        session_start();
        isAuth();

        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = Usuario::find($_SESSION['id']);

            // Sincronizar con los datos del usuario
            $usuario->sincronizar($_POST);

            $alertas = $usuario->nuevo_password();

            if(empty($alertas)){
                $resultado = $usuario->comprobar_password();
                
                if($resultado) {
                    //Asignar el nuevo resultado
                    
                    $usuario->password = $usuario->password_nuevo;
                    
                    //Eliminar propiedades innecesarias
                    unset($usuario->password_actual);
                    unset($usuario->password_nuevo);

                    //Hashear en nuevo password
                    $usuario->hashPassword();

                    //Actualizar
                    $resultado = $usuario->guardar();
                    
                    if($resultado) {
                        Usuario::setAlerta('exito', 'Password Guardado Correctamente');
                        $alertas = $usuario->getAlertas();
                    }                    

                }else{
                    Usuario::setAlerta('error', 'Password incorrecto');
                    $alertas = $usuario->getAlertas();

                }
            }
            
        }

        $router->render('dashboard/cambiar-password', [
            'titulo' => 'Cambiar Password',
            'alertas' => $alertas           
        ]);
    }
}



 