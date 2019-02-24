<?php

    /* Fichero productos.php -> Listado de productos.
     *      - Listar los productos de la tienda.
     *      - Permitir al usuario seleccionar productos que va a comprar.
     */

    session_start(); // Crear o Abrir sesión..
    // Antes comprobar que el usuario esta logueado...
    if(!isset($_SESSION['user'])){
        header("Location: login.php?info='Debes loguearte primero'");
        exit();
    }

    // Cargamos los fichero '.php' que se van a utilizar...
    spl_autoload_register(function ($class){
        if (strpos($class,"Smarty")!==false){
             require_once('Smarty.class.php');  
        } else {
             require_once("./POO/$class.php");  
        }
    });
        
    $view_products = new Smarty(); // Creamos un objeto para gestionar la plantilla Smarty...
    // Configuramos los directorios
    $view_products->template_dir = './Smarty/template/';
    $view_products->compile_dir = './Smarty/template_c/';

    $productos = BBDD::obtenerProductos(); // Obtenemos los producto de la BBDD...
    $cesta = Cesta::getProductos(); // Cargamos la cesta con los productos que haya...
    
    // BTN Opción cesta -> Eliminar un producto, todos, padar... 
    $btn = filter_input(INPUT_POST, 'cesta', FILTER_DEFAULT, FILTER_FORCE_ARRAY);
    foreach ($btn ?? [] as $key => $value) {
        switch ($key) {
            case "eliminar": 
                Cesta::eliminar($value);
                break;

            case "pagar":
                if(isset($_SESSION['producto'])) { // SI hay productos en la cesta...
                    header("Location: pagar.php");
                    exit();
                }
                break;

            case "vaciar":
                if(isset($_SESSION['producto'])) { // SI hay productos en la cesta...
                    Cesta::vaciar();
                }
                break;

            default:
                break;
        }
    }
    
    // BTN Añadir -> Comprobar en la BD el usuario ingresado en el FORM...
    if(filter_input(INPUT_POST, 'btn')){
        $cod = filter_input(INPUT_POST, 'btn'); // Obtenemos el código del producto (Name BTN)...        
        // Productos para la Cesta 
        Cesta::nuevoArticulo($cod); // Añadimos el producto a la cesta...

        $view_products->assign('cestaProductos', $cesta); // Utilizado en cesta.tpl
        $view_products->assign('listaProductos', $productos); // Utilizado en listaProductos.tpl
        $view_products->display('productos.tpl'); // Mostrar plantilla -> Productos
        
    } else {
        // Enviamos un valor a la plantilla Smarty, el array con los productos de la BBDD...
        $view_products->assign('listaProductos', $productos); // Utilizado en listaProductos.tpl
        $view_products->assign('cestaProductos', $cesta); // Instanciar la cesta...  
        $view_products->display('productos.tpl'); // Mostrar plantilla -> Productos
    }