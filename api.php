<?php
// Datos de conexión a la base de datos
$host = 'b4ogxjpclwt26y70k5gq-mysql.services.clever-cloud.com'; // Obtén esto de Clever Cloud
$db = 'b4ogxjpclwt26y70k5gq'; // Nombre de tu base de datos
$user = 'uumtgeq1tnwb4iox'; // Usuario de la base de datos
$pass = 'b4ogxjpclwt26y70k5gq'; // Contraseña del usuario
$port = '3306'; // Normalmente 3306
// Crear conexión
$conn = new mysqli($host, $user, $pass, $db, $port);
// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
// Manejar solicitudes POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $nombres = $_POST['nombres'];
    $apellidos = $_POST['apellidos'];
    $codigo = $_POST['codigo'];
    $telefono = $_POST['telefono'];
    
    // Evitar registros duplicados
    $fecha_actual = date("Y-m-d"); // Solo la fecha
    $hora_actual = date("H:i:s"); // Solo la hora

    // Verificar si el ID existe en la tabla alumnos
    $sqlAlumnosCheck = "SELECT * FROM alumnos WHERE id = '$id'";
    $alumnoResult = $conn->query($sqlAlumnosCheck);

    if ($alumnoResult->num_rows == 0) {
        echo "El ID no existe en la tabla de alumnos.";
        exit();
    }

    // Verificar si el UID ya ha sido registrado en el día actual
    $sqlCheck = "SELECT * FROM asistencia WHERE id = '$id' AND DATE(fecha) = '$fecha_actual'";
    $result = $conn->query($sqlCheck);

    if ($result->num_rows > 0) { // Si ya existe el registro para hoy
        echo "Este UID ya ha sido registrado hoy.";
    } else {
        // Insertar el UID y la fecha y hora actuales
        $sql = "INSERT INTO asistencia (id, nombres, apellidos, codigo, fecha, hora) VALUES ('$id', '$nombres','$apellidos','$codigo', '$fecha_actual', '$hora_actual')";

        if ($conn->query($sql) === TRUE) {
            echo "Registro guardado correctamente";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}
