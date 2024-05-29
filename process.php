<?php
// Define la constante
define("SCHOOL_NAME", "Escuela Ejemplo");

// Clase Estudiante
class Estudiante {
    private $nombre;
    private $edad;
    private $correo;

    public function __construct($nombre, $edad, $correo) {
        $this->nombre = $nombre;
        $this->edad = $edad;
        $this->correo = $correo;
    }

    public function mostrarInformacion() {
        return "Nombre: $this->nombre, Edad: $this->edad, Correo: $this->correo, Escuela: " . SCHOOL_NAME;
    }

    public function getEdad() {
        return $this->edad;
    }
}

// Arreglo para almacenar estudiantes
session_start();
if (!isset($_SESSION['estudiantes'])) {
    $_SESSION['estudiantes'] = array();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['name'];
    $edad = $_POST['age'];
    $correo = $_POST['email'];
    $errores = array();

    // Validación del nombre
    if (empty($nombre)) {
        $errores[] = "El nombre no puede estar vacío.";
    }

    // Validación de la edad
    if (empty($edad) || !filter_var($edad, FILTER_VALIDATE_INT, array("options" => array("min_range" => 1)))) {
        $errores[] = "La edad debe ser un número entero mayor a 0.";
    }

    // Validación del correo electrónico
    if (empty($correo) || !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $errores[] = "El correo electrónico debe tener un formato válido.";
    }

    // Mostrar errores o continuar con el registro
    if (!empty($errores)) {
        foreach ($errores as $error) {
            echo "<script>alert('$error');</script>";
        }
    } else {
        $estudiante = new Estudiante($nombre, $edad, $correo);
        $_SESSION['estudiantes'][] = $estudiante;
        
        // Enviar correo electrónico
        $to = $correo;
        $subject = "Registro Exitoso";
        $message = "Hola $nombre,\n\nTu registro ha sido exitoso.\n\nGracias.";
        $headers = "From: no-reply@example.com";

        if (mail($to, $subject, $message, $headers)) {
            echo "<script>alert('Correo electrónico enviado exitosamente');</script>";
        } else {
            echo "<script>alert('Error al enviar el correo electrónico');</script>";
        }

        echo "<script>alert('Registro exitoso');</script>";
    }
}

// Visualización de datos
echo "<h2>Lista de Estudiantes</h2>";
echo "<table border='1'>";
echo "<tr><th>Nombre</th><th>Edad</th><th>Correo Electrónico</th><th>Escuela</th><th>Clasificación</th></tr>";

foreach ($_SESSION['estudiantes'] as $estudiante) {
    echo "<tr>";
    echo "<td>" . $estudiante->mostrarInformacion() . "</td>";
    $edad = $estudiante->getEdad();
    $clasificacion = "";

    if ($edad < 18) {
        $clasificacion = "Menor de edad";
    } elseif ($edad <= 25) {
        $clasificacion = "Adulto joven";
    } else {
        $clasificacion = "Adulto";
    }

    echo "<td>$clasificacion</td>";
    echo "</tr>";
}

echo "</table>";

// Generar listas de números
echo "<h2>Lista de Números del 1 al 10</h2>";
for ($i = 1; $i <= 10; $i++) {
    echo $i . " ";
}

echo "<h2>Lista de Números del 10 al 1</h2>";
$i = 10;
while ($i >= 1) {
    echo $i . " ";
    $i--;
}

// Estructura switch para niveles de curso
echo "<h2>Nivel de Curso</h2>";
$nivel_curso = 2; // Ejemplo de nivel de curso ingresado por el usuario

switch ($nivel_curso) {
    case 1:
        echo "Nivel Básico";
        break;
    case 2:
        echo "Nivel Intermedio";
        break;
    case 3:
        echo "Nivel Avanzado";
        break;
    default:
        echo "Nivel Desconocido";
}
?>
