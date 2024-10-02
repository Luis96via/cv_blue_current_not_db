<?php


session_start(); // Asegúrate de iniciar la sesión

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cv_antonio";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$data = json_decode(file_get_contents('php://input'), true);
$tiempo_visita = $data['tiempo_visita'];

// Verificar si el ID de la última visita está en la sesión
if (isset($_SESSION['last_visit_id'])) {
    $last_visit_id = $_SESSION['last_visit_id'];

    // Actualizar el tiempo de visita para la última entrada usando el ID de la sesión
    $sql = "UPDATE visitas SET tiempo_visita = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $tiempo_visita, $last_visit_id);
    $stmt->execute();
    $stmt->close();
} else {
    // Si el ID de la última visita no está en la sesión, redirigir a Google
    header("Location: https://www.google.com");
    exit();
}

$conn->close();
?>