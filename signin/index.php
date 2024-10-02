<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css">
    <style>
        body {
            background: #212b2e;
            font-family: 'Lato', sans-serif;
        }
        .register-container {
            max-width: 400px;
            margin: 100px auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .btn-primary {
            background-color: #2ECEF1;
            border-color: #2ECEF1;
        }
        .btn-primary:hover {
            background-color: #cff6ff;
            border-color: #cff6ff;
        }
    </style>
</head>
<body>
<div class="register-container">
    <h2 class="text-center">Registro</h2>
    <?php
    session_start();
    if (!empty($_SESSION['error_message'])) {
        echo "<div class='alert alert-danger' role='alert'>" . $_SESSION['error_message'] . "</div>";
        unset($_SESSION['error_message']);
    }
    if (!empty($_SESSION['success_message'])) {
        echo "<div class='alert alert-success' role='alert'>" . $_SESSION['success_message'] . "</div>";
        
        echo "<script>
            console.log('Redirigiendo...');
            setTimeout(function() {
                window.location.href = '../login/index.php';
            }, 3000);
          </script>";
        
        unset($_SESSION['success_message']);

    }
    ?>
    <form method="POST" action="register.php">
        <div class="form-group">
            <label for="email">Correo Electrónico</label>
            <input type="email" class="form-control" name="email" id="email" placeholder="Introduce tu correo" required>
        </div>
        <div class="form-group">
            <label for="password">Contraseña</label>
            <div class="input-group">
                <input type="password" class="form-control" name="password" id="password" placeholder="Contraseña" required>
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Registrarse</button>
    </form>
</div>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
<script src="https://kit.fontawesome.com/022d283814.js" crossorigin="anonymous"></script>
<script>
    document.getElementById('togglePassword').addEventListener('click', function () {
        const passwordField = document.getElementById('password');
        const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordField.setAttribute('type', type);
        this.querySelector('i').classList.toggle('fa-eye-slash');
    });
</script>
</body>
</html>