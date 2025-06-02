<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

// Incluir archivos
include_once 'config/database.php';
include_once 'models/Docente.php';

// Instanciar DB y conectar
$database = new Database();
$db = $database->connect();

// Instanciar objeto docente
$docente = new Docente($db);

// Obtener método de la solicitud
$metodo = $_SERVER['REQUEST_METHOD'];

// Procesar solicitud según el método
switch($metodo) {
    case 'GET':
        $id = isset($_GET['id']) ? $_GET['id'] : null;

        if ($id) {
            $docente->id_docente = $id;

            if ($docente->leerUno()) {
                echo json_encode([
                    'id_docente' => $docente->id_docente,
                    'cedula' => $docente->cedula,
                    'nombres' => $docente->nombres,
                    'apellidos' => $docente->apellidos,
                    'titulo_academico' => $docente->titulo_academico,
                    'especialidad' => $docente->especialidad,
                    'telefono' => $docente->telefono,
                    'email' => $docente->email,
                    'fecha_contratacion' => $docente->fecha_contratacion,
                    'activo' => $docente->activo
                ]);
            } else {
                http_response_code(404);
                echo json_encode(['mensaje' => 'Docente no encontrado']);
            }
        } else {
            $result = $docente->leer();
            $num = $result->rowCount();

            if ($num > 0) {
                $docentes_arr = [];

                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);

                    $docente_item = [
                        'id_docente' => $id_docente,
                        'cedula' => $cedula,
                        'nombres' => $nombres,
                        'apellidos' => $apellidos,
                        'titulo_academico' => $titulo_academico,
                        'especialidad' => $especialidad,
                        'telefono' => $telefono,
                        'email' => $email,
                        'fecha_contratacion' => $fecha_contratacion,
                        'activo' => $activo
                    ];

                    array_push($docentes_arr, $docente_item);
                }

                echo json_encode($docentes_arr);
            } else {
                http_response_code(404);
                echo json_encode(['mensaje' => 'No se encontraron docentes']);
            }
        }
        break;

// Agrega aquí las funciones POST, PUT y DELETE ajustadas para `docentes`
}
?>
