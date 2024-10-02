<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Análisis de Visitas</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* Estilos adicionales para la tabla */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #2ECEF1;
            color: #fff;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        form {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Panel de Análisis de Visitas</h1>
        
        <!-- Formulario para seleccionar el orden de la tabla -->
        <form method="GET" action="">
            <label for="order">Ordenar por:</label>
            <select name="order" id="order">
                <option value="id">ID</option>
                <option value="ip">IP</option>
                <option value="pais">País</option>
                <option value="region">Región</option>
                <option value="ciudad">Ciudad</option>
                <option value="fecha">Fecha</option>
                <option value="tiempo_visita">Tiempo de Visita</option>
            </select>
            <label for="direction">Dirección:</label>
            <select name="direction" id="direction">
                <option value="ASC">Ascendente</option>
                <option value="DESC">Descendente</option>
            </select>
            <button type="submit">Aplicar</button>
        </form>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>IP</th>
                    <th>País</th>
                    <th>Región</th>
                    <th>Ciudad</th>
                    <th>Fecha</th>
                    <th>Tiempo de Visita (segundos)</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Conexión a la base de datos
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "cv_antonio";
                $conn = new mysqli($servername, $username, $password, $dbname);
                if ($conn->connect_error) {
                    die("Conexión fallida: " . $conn->connect_error);
                }

                // Obtener los criterios de ordenación seleccionados
                $order = isset($_GET['order']) ? $_GET['order'] : 'id';
                $direction = isset($_GET['direction']) ? $_GET['direction'] : 'ASC';

                // Validar los valores para evitar inyecciones SQL
                $allowed_columns = ['id', 'ip', 'pais', 'region', 'ciudad', 'fecha', 'tiempo_visita'];
                if (!in_array($order, $allowed_columns)) {
                    $order = 'id';
                }

                $allowed_directions = ['ASC', 'DESC'];
                if (!in_array($direction, $allowed_directions)) {
                    $direction = 'ASC';
                }

                // Construir la consulta SQL
                $sql = "SELECT * FROM visitas ORDER BY $order $direction";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    // Salida de datos por cada fila
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['id']}</td>
                                <td>{$row['ip']}</td>
                                <td>{$row['pais']}</td>
                                <td>{$row['region']}</td>
                                <td>{$row['ciudad']}</td>
                                <td>{$row['fecha']}</td>
                                <td>{$row['tiempo_visita']}</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No hay datos disponibles</td></tr>";
                }
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>