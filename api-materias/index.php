<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

// Incluir archivos
include_once 'config/database.php';
include_once 'models/Materia.php';

// Instanciar DB y conectar
$database = new Database();
$db = $database->connect();

// Instanciar objeto materia
$materia = new Materia($db);

// Obtener método de la solicitud
$metodo = $_SERVER['REQUEST_METHOD'];

// Procesar solicitud según el método
switch($metodo) {
    case 'GET':
        $id = isset($_GET['id']) ? $_GET['id'] : null;

        if ($id) {
            $materia->id_materia = $id;

            if ($materia->leerUno()) {
                echo json_encode([
                    'id_materia' => $materia->id_materia,
                    'codigo_materia' => $materia->codigo_materia,
                    'nombre_materia' => $materia->nombre_materia,
                    'tota_horas' => $materia->tota_horas,
                    'horas_teoria' => $materia->horas_teoria,
                    'horas_practica' => $materia->horas_practica,
                    'descripcion' => $materia->descripcion
                ]);
            } else {
                http_response_code(404);
                echo json_encode(['mensaje' => 'Materia no encontrada']);
            }
        } else {
            $result = $materia->leer();
            $num = $result->rowCount();

            if ($num > 0) {
                $materias_arr = [];

                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);

                    $materia_item = [
                        'id_materia' => $id_materia,
                        'codigo_materia' => $codigo_materia,
                        'nombre_materia' => $nombre_materia,
                        'tota_horas' => $tota_horas,
                        'horas_teoria' => $horas_teoria,
                        'horas_practica' => $horas_practica,
                        'descripcion' => $descripcion
                    ];

                    array_push($materias_arr, $materia_item);
                }

                echo json_encode($materias_arr);
            } else {
                http_response_code(404);
                echo json_encode(['mensaje' => 'No se encontraron materias']);
            }
        }
        break;

    case 'POST':
        $datos = json_decode(file_get_contents("php://input"));

        if (!empty($datos->codigo_materia) && !empty($datos->nombre_materia)) {
            $materia->codigo_materia = $datos->codigo_materia;
            $materia->nombre_materia = $datos->nombre_materia;
            $materia->tota_horas = $datos->tota_horas ?? 0;
            $materia->horas_teoria = $datos->horas_teoria ?? 0;
            $materia->horas_practica = $datos->horas_practica ?? 0;
            $materia->descripcion = $datos->descripcion ?? '';

            if ($materia->crear()) {
                http_response_code(201);
                echo json_encode(['mensaje' => 'Materia creada']);
            } else {
                http_response_code(503);
                echo json_encode(['mensaje' => 'No se pudo crear la materia']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['mensaje' => 'Datos incompletos']);
        }
        break;

    case 'PUT':
        $id = isset($_GET['id']) ? $_GET['id'] : null;

        if ($id) {
            $datos = json_decode(file_get_contents("php://input"));

            $materia->id_materia = $id;
            $materia->codigo_materia = $datos->codigo_materia ?? '';
            $materia->nombre_materia = $datos->nombre_materia ?? '';
            $materia->tota_horas = $datos->tota_horas ?? 0;
            $materia->horas_teoria = $datos->horas_teoria ?? 0;
            $materia->horas_practica = $datos->horas_practica ?? 0;
            $materia->descripcion = $datos->descripcion ?? '';

            if ($materia->actualizar()) {
                http_response_code(200);
                echo json_encode(['mensaje' => 'Materia actualizada']);
            } else {
                http_response_code(503);
                echo json_encode(['mensaje' => 'No se pudo actualizar la materia']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['mensaje' => 'ID no proporcionado']);
        }
        break;

    case 'DELETE':
        $id = isset($_GET['id']) ? $_GET['id'] : null;

        if ($id) {
            $materia->id_materia = $id;

            if ($materia->eliminar()) {
                http_response_code(200);
                echo json_encode(['mensaje' => 'Materia eliminada']);
            } else {
                http_response_code(503);
                echo json_encode(['mensaje' => 'No se pudo eliminar la materia']);
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
