<?php

require "database.php";

session_start();

if (!isset($_SESSION["user"])) {
  header("Location: login.php");
  return;
}

$id = $_GET["id"];

// Obtener el contacto especificado por su ID
$statement = $conn->prepare("SELECT * FROM contacts WHERE id = :id LIMIT 1");
$statement->execute([":id" => $id]);

if ($statement->rowCount() == 0) {
  http_response_code(404);
  echo ("HTTP 404 NOT FOUND");
  return;
}

$contact = $statement->fetch(PDO::FETCH_ASSOC);

// Verificar si el contacto pertenece al usuario actual
if ($contact["user_id"] !== $_SESSION["user"]["id"]) {
  http_response_code(403);
  echo ("HTTP 403 UNAUTHORIZED");
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

    // Actualizar el contacto en la base de datos
    $statement = $conn->prepare("UPDATE contacts SET name = :name, phone_number = :phone_number WHERE id = :id");
    $statement->execute([
      ":id" => $id,
      ":name" => $_POST["name"],
      ":phone_number" => $_POST["phone_number"],
    ]);

    // Almacenar un mensaje flash en la sesión para mostrarlo en la página de inicio
    $_SESSION["flash"] = ["message" => "Contact {$_POST['name']} updated."];

    // Redirigir al usuario a la página de inicio después de actualizar el contacto
    header("Location: home.php");
  }
}
?>

<?php require "partials/header.php" ?>

<div class="container pt-5">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header">Edit Contact</div>
        <div class="card-body">
          <?php if ($error) : ?>
            <p class="text-danger">
              <?= $error ?>
            </p>
          <?php endif ?>
          <form method="POST" action="edit.php?id=<?= $contact['id'] ?>">
            <div class="mb-3 row">
              <label for="name" class="col-md-4 col-form-label text-md-end">Name</label>
              <div class="col-md-6">
                <input value="<?= $contact['name'] ?>" id="name" type="text" class="form-control" name="name" autocomplete="name" autofocus>
              </div>
            </div>

            <div class="mb-3 row">
              <label for="phone_number" class="col-md-4 col-form-label text-md-end">Phone Number</label>
              <div class="col-md-6">
                <input value="<?= $contact['phone_number'] ?>" id="phone_number" type="tel" class="form-control" name="phone_number" autocomplete="phone_number" autofocus>
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

<!-- Aquí están los comentarios para cada sección del código: -->

<!-- require "database.php";: Esta línea incluye el archivo "database.php", que se supone que contiene la configuración y la conexión a la base de datos.

session_start();: Esta función se utiliza para iniciar la sesión y permitir el acceso a la variable $_SESSION.

if (!isset($_SESSION["user"])) {: Verifica si el usuario ha iniciado sesión. Si no ha iniciado sesión, se redirige al usuario a la página de inicio de sesión utilizando header("Location: login.php"); y se detiene la ejecución del resto del código utilizando return;.

$id = $_GET["id"];: Obtiene el ID del contacto de la URL utilizando $_GET.

$statement = $conn->prepare("SELECT * FROM contacts WHERE id = :id LIMIT 1");: Pre -->
