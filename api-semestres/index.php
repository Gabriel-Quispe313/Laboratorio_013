<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

// Incluir archivos
include_once 'config/database.php';
include_once 'models/Semestre.php';

// Instanciar DB y conectar
$database = new Database();
$db = $database->connect();

// Instanciar objeto semestre
$semestre = new Semestre($db);

// Obtener método de la solicitud
$metodo = $_SERVER['REQUEST_METHOD'];

// Procesar solicitud según el método
switch($metodo) {
    case 'GET':
        $id = isset($_GET['id']) ? $_GET['id'] : null;

        if($id) {
            $semestre->id = $id;

            if($semestre->leerUno()) {
                echo json_encode([
                    'id' => $semestre->id,
                    'numero_semestre' => $semestre->numero_semestre,
                    'nombre_semestre' => $semestre->nombre_semestre,
                    'fecha_inicio' => $semestre->fecha_inicio,
                    'fecha_fin' => $semestre->fecha_fin,
                    'activo' => $semestre->activo
                ]);
            } else {
                http_response_code(404);
                echo json_encode(['mensaje' => 'Semestre no encontrado']);
            }
        } else {
            $result = $semestre->leer();
            $num = $result->rowCount();

            if($num > 0) {
                $semestres_arr = array();

                while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);

                    $semestre_item = array(
                        'id' => $id,
                        'numero_semestre' => $numero_semestre,
                        'nombre_semestre' => $nombre_semestre,
                        'fecha_inicio' => $fecha_inicio,
                        'fecha_fin' => $fecha_fin,
                        'activo' => $activo
                    );

                    array_push($semestres_arr, $semestre_item);
                }

                echo json_encode($semestres_arr);
            } else {
                http_response_code(404);
                echo json_encode(['mensaje' => 'No se encontraron semestres']);
            }
        }
        break;

    case 'POST':
        $datos = json_decode(file_get_contents("php://input"));

        if(!empty($datos->numero_semestre) && !empty($datos->nombre_semestre)) {
            $semestre->numero_semestre = $datos->numero_semestre;
            $semestre->nombre_semestre = $datos->nombre_semestre;
            $semestre->fecha_inicio = $datos->fecha_inicio ?? null;
            $semestre->fecha_fin = $datos->fecha_fin ?? null;
            $semestre->activo = $datos->activo ?? true;

            if($semestre->crear()) {
                http_response_code(201);
                echo json_encode(['mensaje' => 'Semestre creado']);
            } else {
                http_response_code(503);
                echo json_encode(['mensaje' => 'No se pudo crear el semestre']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['mensaje' => 'Datos incompletos']);
        }
        break;

    case 'PUT':
        $id = isset($_GET['id']) ? $_GET['id'] : null;

        if($id) {
            $datos = json_decode(file_get_contents("php://input"));

            $semestre->id = $id;
            $semestre->numero_semestre = $datos->numero_semestre;
            $semestre->nombre_semestre = $datos->nombre_semestre;
            $semestre->fecha_inicio = $datos->fecha_inicio ?? null;
            $semestre->fecha_fin = $datos->fecha_fin ?? null;
            $semestre->activo = $datos->activo ?? true;

            if($semestre->actualizar()) {
                http_response_code(200);
                echo json_encode(['mensaje' => 'Semestre actualizado']);
            } else {
                http_response_code(503);
                echo json_encode(['mensaje' => 'No se pudo actualizar el semestre']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['mensaje' => 'ID no proporcionado']);
        }
        break;

    case 'DELETE':
        $id = isset($_GET['id']) ? $_GET['id'] : null;

        if($id) {
            $semestre->id = $id;

            if($semestre->eliminar()) {
                http_response_code(200);
                echo json_encode(['mensaje' => 'Semestre eliminado']);
            } else {
                http_response_code(503);
                echo json_encode(['mensaje' => 'No se pudo eliminar el semestre']);
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
