<?php
class Docente {
    private $conn;
    private $table = 'docentes';

    public $id_docente;
    public $cedula;
    public $nombres;
    public $apellidos;
    public $titulo_academico;
    public $especialidad;
    public $telefono;
    public $email;
    public $fecha_contratacion;
    public $activo;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Leer todos los docentes
    public function leer() {
        $query = 'SELECT * FROM ' . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Leer un solo docente
    public function leerUno() {
        $query = 'SELECT * FROM ' . $this->table . ' WHERE id_docente = ? LIMIT 1';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id_docente);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $this->cedula = $row['cedula'];
            $this->nombres = $row['nombres'];
            $this->apellidos = $row['apellidos'];
            $this->titulo_academico = $row['titulo_academico'];
            $this->especialidad = $row['especialidad'];
            $this->telefono = $row['telefono'];
            $this->email = $row['email'];
            $this->fecha_contratacion = $row['fecha_contratacion'];
            $this->activo = $row['activo'];
            return true;
        }

        return false;
    }

    // Crear un nuevo docente
    public function crear() {
        $query = 'INSERT INTO ' . $this->table . ' 
                  SET cedula = :cedula, 
                      nombres = :nombres, 
                      apellidos = :apellidos, 
                      titulo_academico = :titulo_academico, 
                      especialidad = :especialidad, 
                      telefono = :telefono, 
                      email = :email, 
                      fecha_contratacion = :fecha_contratacion, 
                      activo = :activo';

        $stmt = $this->conn->prepare($query);

        // Vincular parámetros
        $stmt->bindParam(':cedula', $this->cedula);
        $stmt->bindParam(':nombres', $this->nombres);
        $stmt->bindParam(':apellidos', $this->apellidos);
        $stmt->bindParam(':titulo_academico', $this->titulo_academico);
        $stmt->bindParam(':especialidad', $this->especialidad);
        $stmt->bindParam(':telefono', $this->telefono);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':fecha_contratacion', $this->fecha_contratacion);
        $stmt->bindParam(':activo', $this->activo);

        return $stmt->execute();
    }

    // Actualizar un docente
    public function actualizar() {
        $query = 'UPDATE ' . $this->table . ' 
                  SET cedula = :cedula, 
                      nombres = :nombres, 
                      apellidos = :apellidos, 
                      titulo_academico = :titulo_academico, 
                      especialidad = :especialidad, 
                      telefono = :telefono, 
                      email = :email, 
                      fecha_contratacion = :fecha_contratacion, 
                      activo = :activo 
                  WHERE id_docente = :id_docente';

        $stmt = $this->conn->prepare($query);

        // Vincular parámetros
        $stmt->bindParam(':cedula', $this->cedula);
        $stmt->bindParam(':nombres', $this->nombres);
        $stmt->bindParam(':apellidos', $this->apellidos);
        $stmt->bindParam(':titulo_academico', $this->titulo_academico);
        $stmt->bindParam(':especialidad', $this->especialidad);
        $stmt->bindParam(':telefono', $this->telefono);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':fecha_contratacion', $this->fecha_contratacion);
        $stmt->bindParam(':activo', $this->activo);
        $stmt->bindParam(':id_docente', $this->id_docente);

        return $stmt->execute();
    }

    // Eliminar un docente
    public function eliminar() {
        $query = 'DELETE FROM ' . $this->table . ' WHERE id_docente = :id_docente';
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id_docente', $this->id_docente);

        return $stmt->execute();
    }
}
?>
