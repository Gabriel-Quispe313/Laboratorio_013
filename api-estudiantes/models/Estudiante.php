<?php
class Estudiante {
    private $conn;
    private $table = 'estudiantes';

    public $id;
    public $nombre;
    public $apellido;
    public $edad;
    public $email;
    public $carrera;
    public $fecha_creacion;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Leer todos los estudiantes
    public function leer() {
        $query = 'SELECT * FROM ' . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Leer un solo estudiante
    public function leerUno() {
        $query = 'SELECT * FROM ' . $this->table . ' WHERE id = ? LIMIT 1';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row) {
            $this->nombre = $row['nombre'];
            $this->apellido = $row['apellido'];
            $this->edad = $row['edad'];
            $this->email = $row['email'];
            $this->carrera = $row['carrera'];
            $this->fecha_creacion = $row['fecha_creacion'];
            return true;
        }
        
        return false;
    }

    // Crear estudiante
    public function crear() {
        $query = 'INSERT INTO ' . $this->table . ' 
                  SET nombre = :nombre, 
                      apellido = :apellido, 
                      edad = :edad, 
                      email = :email, 
                      carrera = :carrera';
        
        $stmt = $this->conn->prepare($query);
        
        // Limpiar datos
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->apellido = htmlspecialchars(strip_tags($this->apellido));
        $this->edad = htmlspecialchars(strip_tags($this->edad));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->carrera = htmlspecialchars(strip_tags($this->carrera));
        
        // Vincular parámetros
        $stmt->bindParam(':nombre', $this->nombre);
        $stmt->bindParam(':apellido', $this->apellido);
        $stmt->bindParam(':edad', $this->edad);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':carrera', $this->carrera);
        
        if($stmt->execute()) {
            return true;
        }
        
        return false;
    }

    // Actualizar estudiante
    public function actualizar() {
        $query = 'UPDATE ' . $this->table . ' 
                  SET nombre = :nombre, 
                      apellido = :apellido, 
                      edad = :edad, 
                      email = :email, 
                      carrera = :carrera 
                  WHERE id = :id';
        
        $stmt = $this->conn->prepare($query);
        
        // Limpiar datos
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->apellido = htmlspecialchars(strip_tags($this->apellido));
        $this->edad = htmlspecialchars(strip_tags($this->edad));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->carrera = htmlspecialchars(strip_tags($this->carrera));
        $this->id = htmlspecialchars(strip_tags($this->id));
        
        // Vincular parámetros
        $stmt->bindParam(':nombre', $this->nombre);
        $stmt->bindParam(':apellido', $this->apellido);
        $stmt->bindParam(':edad', $this->edad);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':carrera', $this->carrera);
        $stmt->bindParam(':id', $this->id);
        
        if($stmt->execute()) {
            return true;
        }
        
        return false;
    }

    // Eliminar estudiante
    public function eliminar() {
        $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';
        $stmt = $this->conn->prepare($query);
        
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(':id', $this->id);
        
        if($stmt->execute()) {
            return true;
        }
        
        return false;
    }
}
?>
