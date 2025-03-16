<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, PUT"); // Indica los métodos permitidos (POST para insertar, GET para consultar y listar, PUT para actualizar)
header("Access-Control-Allow-Headers: Content-Type"); // Indica las cabeceras permitidas
header("Content-Type: application/json"); // Especifica el tipo de contenido de la respuesta


require_once('conexion.php');


//CONSULTAR POR UNO
if(isset($_GET['consultar'])){

    $id = $_GET['consultar'];
    $sql = "SELECT * FROM empleados WHERE id=:id";
    $query = $conexion->prepare($sql);
    $query->bindParam('id',$id);
    $query->execute();
    
    if($query->rowCount() > 0){
        $empleado[] = $query->fetch(PDO::FETCH_ASSOC);
        
        echo json_encode($empleado);
        exit();
    }else{
        echo json_encode(["success" => 0]);
        exit();
    }
}

//INSERTAR
if(isset($_GET['insertar'])){
    $data = json_decode( file_get_contents('php://input'), true );
    
   if( isset($data['nombre']) && isset( $data['correo'] ) ){
        $nombre = $data['nombre'];
        $correo = $data['correo'];

        $sql= "INSERT INTO empleados(nombre,correo)
                VALUES(:nombre,:correo);";
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindParam(':nombre',$nombre);
        $sentencia->bindParam(':correo',$correo);
        $sentencia->execute();
        
        if($sentencia){
            echo json_encode(['success'=>1]);
            exit();
        }else{
            echo json_encode(['success'=>0]);
        }

   }else{
    echo json_encode(['error' => 'datos imcompletos.']);
   }
}



//ACTULIZAR
if(isset($_GET['actualizar'])){
    $datos = json_decode( file_get_contents( 'php://input' ), true );
    
    $id = $datos['id'];
    $nombre = $datos['nombre'];
    $correo = $datos['correo'];

    $sql = "UPDATE empleados SET nombre = :nombre, correo = :correo WHERE id = :id";
    $sentencia = $conexion->prepare($sql);
    $sentencia->bindParam(':id',$id);
    $sentencia->bindParam(':nombre',$nombre);
    $sentencia->bindParam(':correo',$correo);
    $sentencia->execute();


    if($sentencia->rowCount() > 0){
        echo json_encode(['success'=>1 ]);
        exit();
    }else{
        echo json_encode(['success'=>0 ]);
        exit();

    } 
    
}


//BORRAR 

if(isset($_GET['borrar'])){
    $id =  $_GET['borrar'];

    $sql= "DELETE FROM empleados WHERE id = :id";
    $sentencia = $conexion->prepare($sql);
    $sentencia->bindParam(':id',$id);
    $sentencia->execute();
    
    if($sentencia->rowCount() > 0){
        echo json_encode( ["respuesta" => "El empleado ha sido eliminado exitosamente."] );
        exit();       
    }else{
        echo json_encode( ["respuesta" => "No ha sido posible eliminar al empleado."] );
        exit();
    }
}





//LISTAR TODOS
$sql = "SELECT * FROM empleados";
$query = $conexion->prepare($sql);
$query->execute();

$empleados = $query->fetchAll();
if( $empleados ){
    echo json_encode($empleados);
    exit();
}else{
    echo json_encode([["success"=>0]]);
    exit();
}

?>