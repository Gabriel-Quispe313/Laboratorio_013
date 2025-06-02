<?php
class Semestre {
    private $conn;
    private $table = 'semestres';

    public $id;
    public $numero_semestre;
    public $nombre_semestre;
    public $fecha_inicio;
    public $fecha_fin;
    public $activo;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Leer todos los semestres
    public function leer() {
        $query = 'SELECT * FROM ' . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Leer un solo semestre
    public function leerUno() {
        $query = 'SELECT * FROM ' . $this->table . ' WHERE id = ? LIMIT 1';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($row) {
            $this->numero_semestre = $row['numero_semestre'];
            $this->nombre_semestre = $row['nombre_semestre'];
            $this->fecha_inicio = $row['fecha_inicio'];
            $this->fecha_fin = $row['fecha_fin'];
            $this->activo = $row['activo'];
            return true;
        }
        
        return false;
    }

    // Crear un nuevo semestre
    public function crear() {
        $query = 'INSERT INTO ' . $this->table . ' 
                  SET numero_semestre = :numero_semestre, 
                      nombre_semestre = :nombre_semestre, 
                      fecha_inicio = :fecha_inicio, 
                      fecha_fin = :fecha_fin, 
                      activo = :activo';
        
        $stmt = $this->conn->prepare($query);
        
        // Limpiar datos
        $this->numero_semestre = htmlspecialchars(strip_tags($this->numero_semestre));
        $this->nombre_semestre = htmlspecialchars(strip_tags($this->nombre_semestre));
        $this->fecha_inicio = htmlspecialchars(strip_tags($this->fecha_inicio));
        $this->fecha_fin = htmlspecialchars(strip_tags($this->fecha_fin));
        $this->activo = htmlspecialchars(strip_tags($this->activo));
        
        // Vincular parámetros
        $stmt->bindParam(':numero_semestre', $this->numero_semestre);
        $stmt->bindParam(':nombre_semestre', $this->nombre_semestre);
        $stmt->bindParam(':fecha_inicio', $this->fecha_inicio);
        $stmt->bindParam(':fecha_fin', $this->fecha_fin);
        $stmt->bindParam(':activo', $this->activo);
        
        return $stmt->execute();
    }

    // Actualizar un semestre
    public function actualizar() {
        $query = 'UPDATE ' . $this->table . ' 
                  SET numero_semestre = :numero_semestre, 
                      nombre_semestre = :nombre_semestre, 
                      fecha_inicio = :fecha_inicio, 
                      fecha_fin = :fecha_fin, 
                      activo = :activo 
                  WHERE id = :id';
        
        $stmt = $this->conn->prepare($query);
        
        // Limpiar datos
        $this->numero_semestre = htmlspecialchars(strip_tags($this->numero_semestre));
        $this->nombre_semestre = htmlspecialchars(strip_tags($this->nombre_semestre));
        $this->fecha_inicio = htmlspecialchars(strip_tags($this->fecha_inicio));
        $this->fecha_fin = htmlspecialchars(strip_tags($this->fecha_fin));
        $this->activo = htmlspecialchars(strip_tags($this->activo));
        $this->id = htmlspecialchars(strip_tags($this->id));
        
        // Vincular parámetros
        $stmt->bindParam(':numero_semestre', $this->numero_semestre);
        $stmt->bindParam(':nombre_semestre', $this->nombre_semestre);
        $stmt->bindParam(':fecha_inicio', $this->fecha_inicio);
        $stmt->bindParam(':fecha_fin', $this->fecha_fin);
        $stmt->bindParam(':activo', $this->activo);
        $stmt->bindParam(':id', $this->id);
        
        return $stmt->execute();
    }

    // Eliminar un semestre
    public function eliminar() {
        $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';
        $stmt = $this->conn->prepare($query);
        
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(':id', $this->id);
        
        return $stmt->execute();
    }
}
?>
