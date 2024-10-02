<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cv_antonio";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Consulta para verificar el correo electrónico
    $sql = "SELECT * FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Verificar la contraseña
        if (password_verify($password, $user['password'])) {
            // Redirigir a la página de éxito
            header("Location: ../office/index.php");
            exit();
        } else {
            $error_message = "Contraseña incorrecta.";
        }
    } else {
        $error_message = "Correo electrónico no encontrado.";
    }
    $stmt->close();
}

$conn->close();

// Redirigir a index.php si hay un error
if (!empty($error_message)) {
    $_SESSION['error_message'] = $error_message;
    header("Location: index.php");
    exit();
}
?>