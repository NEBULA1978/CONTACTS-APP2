<?php

require "database.php";

session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION["user"])) {
  header("Location: login.php");
  return;
}

$error = null;

// Verificar si la solicitud es de tipo POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Verificar si alguno de los campos está vacío
  if (empty($_POST["name"]) || empty($_POST["phone_number"])) {
    $error = "Please fill all the fields."; // Por favor, completa todos los campos.
  } else if (strlen($_POST["phone_number"]) < 9) {
    $error = "Phone number must be at least 9 characters."; // El número de teléfono debe tener al menos 9 caracteres.
  } else {
    $name = $_POST["name"];
    $phoneNumber = $_POST["phone_number"];

    // Preparar y ejecutar la consulta para insertar un nuevo contacto en la base de datos
    $statement = $conn->prepare("INSERT INTO contacts (user_id, name, phone_number) VALUES ({$_SESSION['user']['id']}, :name, :phone_number)");
    $statement->bindParam(":name", $_POST["name"]);
    $statement->bindParam(":phone_number", $_POST["phone_number"]);
    $statement->execute();

    // Almacenar un mensaje flash en la sesión para mostrarlo en la página de inicio
    $_SESSION["flash"] = ["message" => "Contact {$_POST['name']} added."];

    // Redirigir al usuario a la página de inicio después de agregar el contacto
    header("Location: home.php");
    return;
  }
}
?>


<?php require "partials/header.php" ?>

<div class="container pt-5">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header">Add New Contact</div>
        <div class="card-body">
          <?php if ($error) : ?>
            <p class="text-danger">
              <?= $error ?>
            </p>
          <?php endif ?>
          <form method="POST" action="add.php">
            <div class="mb-3 row">
              <label for="name" class="col-md-4 col-form-label text-md-end">Name</label>

              <div class="col-md-6">
                <input id="name" type="text" class="form-control" name="name" autocomplete="name" autofocus>
              </div>
            </div>

            <div class="mb-3 row">
              <label for="phone_number" class="col-md-4 col-form-label text-md-end">Phone Number</label>

              <div class="col-md-6">
                <input id="phone_number" type="tel" class="form-control" name="phone_number" autocomplete="phone_number" autofocus>
              </div>
            </div>

            <div class="mb-3 row">
              <div class="col-md-6 offset-md-4">
                <button type="submit" class="btn btn-primary">Submit</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>


<?php require "partials/footer.php" ?>


<!-- Aquí están los comentarios para cada sección del código:

require "database.php";: Esta línea incluye el archivo "database.php", que se supone que contiene la configuración y la conexión a la base de datos.

session_start();: Esta función se utiliza para iniciar la sesión y permitir el acceso a la variable $_SESSION.

if (!isset($_SESSION["user"])) {: Verifica si el usuario ha iniciado sesión. Si no ha iniciado sesión, se redirige al usuario a la página de inicio de sesión utilizando header("Location: login.php"); y se detiene la ejecución del resto del código utilizando return;.

$error = null;: Inicializa la variable $error como nula.

if ($_SERVER["REQUEST_METHOD"] == "POST") {: Verifica si la solicitud es de tipo POST.

if (empty($_POST["name"]) || empty($_POST["phone_number"])) {: Verifica si alguno de los campos está vacío.

} else if (strlen($_POST["phone_number"]) < 9) {: Verifica si la longitud del número de teléfono es menor a 9 caracteres. $name=$_POST["name"];: Almacena el valor del campo "name" en la variable $name. $phoneNumber=$_POST["phone_number"];: Almacena el valor del campo "phone_number" en la variable $phoneNumber. $statement=$conn->prepare("INSERT INTO contacts (user_id, name, phone_number) VALUES ({$_SESSION['user']['id']}, :name, :phone_number)");: Prepara una consulta para insertar un nuevo contacto en la base de datos, con el ID de usuario obtenido de $_SESSION['user']['id'].

  $statement->bindParam(":name", $_POST["name"]);: Vincula el parámetro :name con el valor del campo "name" del formulario.

  $statement->bindParam(":phone_number", $_POST["phone_number"]);: Vincula el parámetro :phone_number con el valor del campo "phone_number" del formulario.

  $_SESSION["flash"] = ["message" => "Contact {$_POST['name']} added."];: Almacena un mensaje flash en la sesión para mostrarlo en la página de inicio después de agregar el contacto.

  header("Location: home.php");: Redirige al usuario a la página de inicio después de agregar el contacto. -->
