<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

// Incluir archivos
include_once 'config/database.php';
include_once 'models/Estudiante.php';

// Instanciar DB y conectar
$database = new Database();
$db = $database->connect();

// Instanciar objeto estudiante
$estudiante = new Estudiante($db);

// Obtener método de la solicitud
$metodo = $_SERVER['REQUEST_METHOD'];

// Procesar solicitud según el método
switch($metodo) {
    case 'GET':
        // Obtener ID si existe
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        
        if($id) {
            // Leer un solo estudiante
            $estudiante->id = $id;
            
            if($estudiante->leerUno()) {
                echo json_encode([
                    'id' => $estudiante->id,
                    'nombre' => $estudiante->nombre,
                    'apellido' => $estudiante->apellido,
                    'edad' => $estudiante->edad,
                    'email' => $estudiante->email,
                    'carrera' => $estudiante->carrera,
                    'fecha_creacion' => $estudiante->fecha_creacion
                ]);
            } else {
                http_response_code(404);
                echo json_encode(['mensaje' => 'Estudiante no encontrado']);
            }
        } else {
            // Leer todos los estudiantes
            $result = $estudiante->leer();
            $num = $result->rowCount();
            
            if($num > 0) {
                $estudiantes_arr = array();
                
                while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                    
                    $estudiante_item = array(
                        'id' => $id,
                        'nombre' => $nombre,
                        'apellido' => $apellido,
                        'edad' => $edad,
                        'email' => $email,
                        'carrera' => $carrera,
                        'fecha_creacion' => $fecha_creacion
                    );
                    
                    array_push($estudiantes_arr, $estudiante_item);
                }
                
                echo json_encode($estudiantes_arr);
            } else {
                http_response_code(404);
                echo json_encode(['mensaje' => 'No se encontraron estudiantes']);
            }
        }
        break;
        
    case 'POST':
        // Obtener datos del cuerpo de la solicitud
        $datos = json_decode(file_get_contents("php://input"));
        
        if(!empty($datos->nombre) && !empty($datos->apellido) && !empty($datos->email)) {
            $estudiante->nombre = $datos->nombre;
            $estudiante->apellido = $datos->apellido;
            $estudiante->edad = $datos->edad ?? null;
            $estudiante->email = $datos->email;
            $estudiante->carrera = $datos->carrera ?? null;
            
            if($estudiante->crear()) {
                http_response_code(201);
                echo json_encode(['mensaje' => 'Estudiante creado']);
            } else {
                http_response_code(503);
                echo json_encode(['mensaje' => 'No se pudo crear el estudiante']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['mensaje' => 'Datos incompletos']);
        }
        break;
        
    case 'PUT':
        // Obtener ID
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        
        if($id) {
            $datos = json_decode(file_get_contents("php://input"));
            
            $estudiante->id = $id;
            $estudiante->nombre = $datos->nombre;
            $estudiante->apellido = $datos->apellido;
            $estudiante->edad = $datos->edad ?? null;
            $estudiante->email = $datos->email;
            $estudiante->carrera = $datos->carrera ?? null;
            
            if($estudiante->actualizar()) {
                http_response_code(200);
                echo json_encode(['mensaje' => 'Estudiante actualizado']);
            } else {
                http_response_code(503);
                echo json_encode(['mensaje' => 'No se pudo actualizar el estudiante']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['mensaje' => 'ID no proporcionado']);
        }
        break;
        
    case 'DELETE':
        // Obtener ID
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        
        if($id) {
            $estudiante->id = $id;
            
            if($estudiante->eliminar()) {
                http_response_code(200);
                echo json_encode(['mensaje' => 'Estudiante eliminado']);
            } else {
                http_response_code(503);
                echo json_encode(['mensaje' => 'No se pudo eliminar el estudiante']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['mensaje' => 'ID no proporcionado']);
        }
        break;
        
    default:
        http_response_code(405);
        echo json_encode(['mensaje' => 'Método no permitido']);
        break;
}
?>
