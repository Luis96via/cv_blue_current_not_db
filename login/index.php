<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css">
    <style>
        body {
            background: #212b2e; /* color(gray, base) */
            font-family: 'Lato', sans-serif;
        }
        .login-container {
            max-width: 400px;
            margin: 100px auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .btn-primary {
            background-color: #2ECEF1; /* color(primary, base) */
            border-color: #2ECEF1;
        }
        .btn-primary:hover {
            background-color: #cff6ff; /* color(primary, light) */
            border-color: #cff6ff;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2 class="text-center">Iniciar Sesión</h2>
        <?php
    session_start();
    if (!empty($_SESSION['error_message'])) {
        echo "<div class='alert alert-danger' role='alert'>" . $_SESSION['error_message'] . "</div>";
        unset($_SESSION['error_message']);
    }
    if (!empty($_SESSION['success_message'])) {
        echo "<div class='alert alert-success' role='alert'>" . $_SESSION['success_message'] . "</div>";
        unset($_SESSION['success_message']);

    }
    ?>
        <form method="POST" action="login.php">
    <div class="form-group">
        <label for="email">Correo Electrónico</label>
        <input type="email" class="form-control" name="email" id="email" placeholder="Introduce tu correo" required>
    </div>
    <div class="form-group">
        <label for="password">Contraseña</label>
        <input type="password" class="form-control" name="password" id="password" placeholder="Contraseña" required>
    </div>
    <button type="submit" class="btn btn-primary btn-block">Iniciar Sesión</button>

            <button type="submit" class="btn btn-secondary btn-block">Iniciar con Google</button>
        </form>
    </div>
</body>
</html>