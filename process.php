<?php
// Include PHPMailer library
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

// Define la constante
define("SCHOOL_NAME", "Escuela Ejemplo");

// Clase Estudiante
class Estudiante {
    private $nombre;
    private $edad;
    private $correo;
    private $escuela;

    public function __construct($nombre, $edad, $correo, $escuela) {
        $this->nombre = $nombre;
        $this->edad = $edad;
        $this->correo = $correo;
        $this->escuela = $escuela;
    }

    public function mostrarNombre() {
        return $this->nombre;
    }

    public function mostrarEdad() {
        return $this->edad;
    }

    public function mostrarCorreo() {
        return $this->correo;
    }

    public function mostrarEscuela() {
        return $this->escuela;
    }

    public function getClasificacion() {
        $clasificacion = "";

        if ($this->edad < 18) {
            $clasificacion = "Menor de edad";
        } elseif ($this->edad <= 25) {
            $clasificacion = "Adulto joven";
        } else {
            $clasificacion = "Adulto";
        }

        return $clasificacion;
    }
}

// Arreglo para almacenar estudiantes
session_start();
if (!isset($_SESSION['estudiantes'])) {
    $_SESSION['estudiantes'] = array();
}

// Limpiar la lista de estudiantes si se envió un parámetro en la URL llamado 'limpiar'
if (isset($_GET['limpiar'])) {
    $_SESSION['estudiantes'] = array(); // Vaciar el array de estudiantes
}

// Si el array de estudiantes no está inicializado, inicializarlo como un array vacío
if (!isset($_SESSION['estudiantes'])) {
    $_SESSION['estudiantes'] = array();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['name'] ?? '';
    $edad = $_POST['age'] ?? '';
    $correo = $_POST['email'] ?? '';
    $escuela = $_POST['school'] ?? '';
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
        $estudiante = new Estudiante($nombre, $edad, $correo, $escuela);
        $_SESSION['estudiantes'][] = $estudiante;
        
        // Enviar correo electrónico usando PHPMailer
        $mail = new PHPMailer(true);
        try {
            // Configuración del servidor
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'proyecto_registro_collazo@hotmail.com'; // Reemplaza con tu correo
            $mail->Password = 'ALGOf@cil123'; // Reemplaza con tu contraseña o contraseña de aplicación
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            // Habilitar el depurador solo en desarrollo
            // $mail->SMTPDebug = 2; // 2 muestra mensajes detallados de depuración

            // Configuración del correo
            $mail->setFrom('proyecto_registro_collazo@hotmail.com', 'Registro de Estudiantes');
            $mail->addAddress($correo);

            $mail->isHTML(true);
            $mail->Subject = 'Registro Exitoso';
            $mail->Body    = "Hola $nombre,<br><br>Tu registro ha sido exitoso.<br><br>Gracias.";

            $mail->send();
            echo "<script>alert('Correo electrónico enviado exitosamente');</script>";
        } catch (Exception $e) {
            echo "<script>alert('Error al enviar el correo electrónico: {$mail->ErrorInfo}');</script>";
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
    echo "<td>" . $estudiante->mostrarNombre() . "</td>";
    echo "<td>" . $estudiante->mostrarEdad() . "</td>";
    echo "<td>" . $estudiante->mostrarCorreo() . "</td>";
    echo "<td>" . $estudiante->mostrarEscuela() . "</td>";
    echo "<td>" . $estudiante->getClasificacion() . "</td>";
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
