<?php
session_start();

// Configuración de la base de datos
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

// Inicializar mensaje de error
$error_message = "";

// Verificar si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validar el correo electrónico
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error_message'] = "Correo electrónico no válido.";
    } else {
        // Verificar si el correo ya existe
        $sql = "SELECT * FROM usuarios WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $_SESSION['error_message'] = "El correo electrónico ya está registrado.";
        } else {
            // Encriptar la contraseña
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            // Insertar el nuevo usuario en la base de datos
            $sql = "INSERT INTO usuarios (email, password) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $email, $hashed_password);

            if ($stmt->execute()) {
                $_SESSION['success_message'] = "Registro exitoso, serás redirigido en 3 segundos...";
                echo "<script>
                console.log(\"Redirigiendo...\");
                        setTimeout(function() {
                            window.location.href = 'index.php';
                        }, 500);
                      </script>";
            }
        }
        $stmt->close();
    }
}
$conn->close();
header("Location: index.php");
exit();
?>