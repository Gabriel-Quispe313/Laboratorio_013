<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

// Incluir archivos
include_once 'config/database.php';
include_once 'models/Carrera.php';

// Instanciar DB y conectar
$database = new Database();
$db = $database->connect();

// Instanciar objeto carrera
$carrera = new Carrera($db);

// Obtener método de la solicitud
$metodo = $_SERVER['REQUEST_METHOD'];

// Procesar solicitud según el método
switch($metodo) {
    case 'GET':
        // Obtener ID si existe
        $id = isset($_GET['id_carrera']) ? $_GET['id_carrera'] : null;
        
        if($id) {
            // Leer una sola carrera
            $carrera->id_carrera = $id;
            
            if($carrera->leerUno()) {
                echo json_encode([
                    'id_carrera' => $carrera->id_carrera,
                    'nombre_carrera' => $carrera->nombre_carrera,
                    'codigo_carrera' => $carrera->codigo_carrera,
                    'duracion_semestres' => $carrera->duracion_semestres,
                    'descripcion' => $carrera->descripcion,
                    'fecha_creacion' => $carrera->fecha_creacion,
                    'activa' => $carrera->activa
                ]);
            } else {
                http_response_code(404);
                echo json_encode(['mensaje' => 'Carrera no encontrada']);
            }
        } else {
            // Leer todas las carreras
            $result = $carrera->leer();
            $num = $result->rowCount();
            
            if($num > 0) {
                $carreras_arr = array();
                
                while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                    
                    $carrera_item = array(
                        'id_carrera' => $id_carrera,
                        'nombre_carrera' => $nombre_carrera,
                        'codigo_carrera' => $codigo_carrera,
                        'duracion_semestres' => $duracion_semestres,
                        'descripcion' => $descripcion,
                        'fecha_creacion' => $fecha_creacion,
                        'activa' => $activa
                    );
                    
                    array_push($carreras_arr, $carrera_item);
                }
                
                echo json_encode($carreras_arr);
            } else {
                http_response_code(404);
                echo json_encode(['mensaje' => 'No se encontraron carreras']);
            }
        }
        break;
        
    case 'POST':
        // Obtener datos del cuerpo de la solicitud
        $datos = json_decode(file_get_contents("php://input"));
        
        if(!empty($datos->nombre_carrera) && !empty($datos->codigo_carrera) && !empty($datos->duracion_semestres)) {
            $carrera->nombre_carrera = $datos->nombre_carrera;
            $carrera->codigo_carrera = $datos->codigo_carrera;
            $carrera->duracion_semestres = $datos->duracion_semestres;
            $carrera->descripcion = $datos->descripcion ?? null;
            $carrera->fecha_creacion = $datos->fecha_creacion ?? null;
            $carrera->activa = $datos->activa ?? true;
            
            if($carrera->crear()) {
                http_response_code(201);
                echo json_encode(['mensaje' => 'Carrera creada']);
            } else {
                http_response_code(503);
                echo json_encode(['mensaje' => 'No se pudo crear la carrera']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['mensaje' => 'Datos incompletos']);
        }
        break;
        
    case 'PUT':
        // Obtener ID
        $id = isset($_GET['id_carrera']) ? $_GET['id_carrera'] : null;
        
        if($id) {
            $datos = json_decode(file_get_contents("php://input"));
            
            $carrera->id_carrera = $id;
            $carrera->nombre_carrera = $datos->nombre_carrera;
            $carrera->codigo_carrera = $datos->codigo_carrera;
            $carrera->duracion_semestres = $datos->duracion_semestres;
            $carrera->descripcion = $datos->descripcion ?? null;
            $carrera->fecha_creacion = $datos->fecha_creacion ?? null;
            $carrera->activa = $datos->activa ?? true;
            
            if($carrera->actualizar()) {
                http_response_code(200);
                echo json_encode(['mensaje' => 'Carrera actualizada']);
            } else {
                http_response_code(503);
                echo json_encode(['mensaje' => 'No se pudo actualizar la carrera']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['mensaje' => 'ID no proporcionado']);
        }
        break;
        
    case 'DELETE':
        // Obtener ID
        $id = isset($_GET['id_carrera']) ? $_GET['id_carrera'] : null;
        
        if($id) {
            $carrera->id_carrera = $id;
            
            if($carrera->eliminar()) {
                http_response_code(200);
                echo json_encode(['mensaje' => 'Carrera eliminada']);
            } else {
                http_response_code(503);
                echo json_encode(['mensaje' => 'No se pudo eliminar la carrera']);
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
