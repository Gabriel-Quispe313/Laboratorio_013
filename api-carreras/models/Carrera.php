<?php
class Carrera {
    private $conn;
    private $table = 'carreras';

    public $id_carrera;
    public $nombre_carrera;
    public $codigo_carrera;
    public $duracion_semestres;
    public $descripcion;
    public $fecha_creacion;
    public $activa;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Leer todas las carreras
    public function leer() {
        $query = 'SELECT * FROM ' . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Leer una sola carrera
    public function leerUno() {
        $query = 'SELECT * FROM ' . $this->table . ' WHERE id_carrera = ? LIMIT 1';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id_carrera);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($row) {
            $this->nombre_carrera = $row['nombre_carrera'];
            $this->codigo_carrera = $row['codigo_carrera'];
            $this->duracion_semestres = $row['duracion_semestres'];
            $this->descripcion = $row['descripcion'];
            $this->fecha_creacion = $row['fecha_creacion'];
            $this->activa = $row['activa'];
            return true;
        }
        
        return false;
    }

    // Crear una nueva carrera
    public function crear() {
        $query = 'INSERT INTO ' . $this->table . ' 
                  SET nombre_carrera = :nombre_carrera, 
                      codigo_carrera = :codigo_carrera, 
                      duracion_semestres = :duracion_semestres, 
                      descripcion = :descripcion, 
                      fecha_creacion = :fecha_creacion, 
                      activa = :activa';
        
        $stmt = $this->conn->prepare($query);
        
        // Limpiar datos
        $this->nombre_carrera = htmlspecialchars(strip_tags($this->nombre_carrera));
        $this->codigo_carrera = htmlspecialchars(strip_tags($this->codigo_carrera));
        $this->duracion_semestres = htmlspecialchars(strip_tags($this->duracion_semestres));
        $this->descripcion = htmlspecialchars(strip_tags($this->descripcion));
        $this->fecha_creacion = htmlspecialchars(strip_tags($this->fecha_creacion));
        $this->activa = htmlspecialchars(strip_tags($this->activa));
        
        // Vincular parámetros
        $stmt->bindParam(':nombre_carrera', $this->nombre_carrera);
        $stmt->bindParam(':codigo_carrera', $this->codigo_carrera);
        $stmt->bindParam(':duracion_semestres', $this->duracion_semestres);
        $stmt->bindParam(':descripcion', $this->descripcion);
        $stmt->bindParam(':fecha_creacion', $this->fecha_creacion);
        $stmt->bindParam(':activa', $this->activa);
        
        if ($stmt->execute()) {
            return true;
        }
        
        return false;
    }

    // Actualizar una carrera
    public function actualizar() {
        $query = 'UPDATE ' . $this->table . ' 
                  SET nombre_carrera = :nombre_carrera, 
                      codigo_carrera = :codigo_carrera, 
                      duracion_semestres = :duracion_semestres, 
                      descripcion = :descripcion, 
                      fecha_creacion = :fecha_creacion, 
                      activa = :activa 
                  WHERE id_carrera = :id_carrera';
        
        $stmt = $this->conn->prepare($query);
        
        // Limpiar datos
        $this->nombre_carrera = htmlspecialchars(strip_tags($this->nombre_carrera));
        $this->codigo_carrera = htmlspecialchars(strip_tags($this->codigo_carrera));
        $this->duracion_semestres = htmlspecialchars(strip_tags($this->duracion_semestres));
        $this->descripcion = htmlspecialchars(strip_tags($this->descripcion));
        $this->fecha_creacion = htmlspecialchars(strip_tags($this->fecha_creacion));
        $this->activa = htmlspecialchars(strip_tags($this->activa));
        $this->id_carrera = htmlspecialchars(strip_tags($this->id_carrera));
        
        // Vincular parámetros
        $stmt->bindParam(':nombre_carrera', $this->nombre_carrera);
        $stmt->bindParam(':codigo_carrera', $this->codigo_carrera);
        $stmt->bindParam(':duracion_semestres', $this->duracion_semestres);
        $stmt->bindParam(':descripcion', $this->descripcion);
        $stmt->bindParam(':fecha_creacion', $this->fecha_creacion);
        $stmt->bindParam(':activa', $this->activa);
        $stmt->bindParam(':id_carrera', $this->id_carrera);
        
        if ($stmt->execute()) {
            return true;
        }
        
        return false;
    }

    // Eliminar una carrera
    public function eliminar() {
        $query = 'DELETE FROM ' . $this->table . ' WHERE id_carrera = :id_carrera';
        $stmt = $this->conn->prepare($query);
        
        $this->id_carrera = htmlspecialchars(strip_tags($this->id_carrera));
        $stmt->bindParam(':id_carrera', $this->id_carrera);
        
        if ($stmt->execute()) {
            return true;
        }
        
        return false;
    }
}
?>
