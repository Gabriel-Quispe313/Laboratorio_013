<?php
class Materia {
    private $conn;
    private $table = 'materias';

    public $id_materia;
    public $codigo_materia;
    public $nombre_materia;
    public $tota_horas;
    public $horas_teoria;
    public $horas_practica;
    public $descripcion;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Leer todas las materias
    public function leer() {
        $query = 'SELECT * FROM ' . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Leer una sola materia
    public function leerUno() {
        $query = 'SELECT * FROM ' . $this->table . ' WHERE id_materia = ? LIMIT 1';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id_materia);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $this->codigo_materia = $row['codigo_materia'];
            $this->nombre_materia = $row['nombre_materia'];
            $this->tota_horas = $row['tota_horas'];
            $this->horas_teoria = $row['horas_teoria'];
            $this->horas_practica = $row['horas_practica'];
            $this->descripcion = $row['descripcion'];
            return true;
        }

        return false;
    }

    // Crear una nueva materia
    public function crear() {
        $query = 'INSERT INTO ' . $this->table . ' 
                  SET codigo_materia = :codigo_materia, 
                      nombre_materia = :nombre_materia, 
                      tota_horas = :tota_horas, 
                      horas_teoria = :horas_teoria, 
                      horas_practica = :horas_practica, 
                      descripcion = :descripcion';

        $stmt = $this->conn->prepare($query);

        // Vincular parámetros
        $stmt->bindParam(':codigo_materia', $this->codigo_materia);
        $stmt->bindParam(':nombre_materia', $this->nombre_materia);
        $stmt->bindParam(':tota_horas', $this->tota_horas);
        $stmt->bindParam(':horas_teoria', $this->horas_teoria);
        $stmt->bindParam(':horas_practica', $this->horas_practica);
        $stmt->bindParam(':descripcion', $this->descripcion);

        return $stmt->execute();
    }

    // Actualizar una materia
    public function actualizar() {
        $query = 'UPDATE ' . $this->table . ' 
                  SET codigo_materia = :codigo_materia, 
                      nombre_materia = :nombre_materia, 
                      tota_horas = :tota_horas, 
                      horas_teoria = :horas_teoria, 
                      horas_practica = :horas_practica, 
                      descripcion = :descripcion 
                  WHERE id_materia = :id_materia';

        $stmt = $this->conn->prepare($query);

        // Vincular parámetros
        $stmt->bindParam(':codigo_materia', $this->codigo_materia);
        $stmt->bindParam(':nombre_materia', $this->nombre_materia);
        $stmt->bindParam(':tota_horas', $this->tota_horas);
        $stmt->bindParam(':horas_teoria', $this->horas_teoria);
        $stmt->bindParam(':horas_practica', $this->horas_practica);
        $stmt->bindParam(':descripcion', $this->descripcion);
        $stmt->bindParam(':id_materia', $this->id_materia);

        return $stmt->execute();
    }

    // Eliminar una materia
    public function eliminar() {
        $query = 'DELETE FROM ' . $this->table . ' WHERE id_materia = :id_materia';
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id_materia', $this->id_materia);

        return $stmt->execute();
    }
}
?>
