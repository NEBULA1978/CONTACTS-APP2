<?php

require "database.php";

// session_start();

// if (isset($_SESSION["user"])) {
//   header("Location: home.php");
//   return;
// }

$error = null;

// Verificar si la solicitud es de tipo POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Verificar si alguno de los campos está vacío
  if (empty($_POST["email"]) || empty($_POST["password"])) {
    $error = "Please fill all the fields."; // Por favor, completa todos los campos.
  } else if (!str_contains($_POST["email"], "@")) {
    $error = "Email format is incorrect."; // El formato del correo electrónico es incorrecto.
  } else {
    // Preparar y ejecutar una consulta para obtener el usuario con el correo electrónico proporcionado
    $statement = $conn->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
    $statement->bindParam(":email", $_POST["email"]);
    $statement->execute();

    // Verificar si se encontró algún registro con el correo electrónico proporcionado
    if ($statement->rowCount() == 0) {
      $error = "Invalid credentials."; // Credenciales inválidas.
    } else {
      // Obtener el usuario de la base de datos
      $user = $statement->fetch(PDO::FETCH_ASSOC);

      // Verificar si la contraseña proporcionada coincide con la contraseña almacenada (se utiliza la función password_verify())
      if (!password_verify($_POST["password"], $user["password"])) {
        $error = "Invalid credentials."; // Credenciales inválidas.
      } else {
        // Iniciar sesión y almacenar la información del usuario en la sesión
        session_start();

        // Se elimina la contraseña del arreglo del usuario para no almacenarla en la sesión
        unset($user["password"]);

        $_SESSION["user"] = $user;

        // Redirigir al usuario a la página de inicio después del inicio de sesión exitoso
        header("Location: home.php");
      }
    }
  }
}
?>

<?php require "partials/header.php" ?>

<div class="container pt-5">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header">Login</div>
        <div class="card-body">
          <?php if ($error) : ?>
            <p class="text-danger">
              <?= $error ?>
            </p>
          <?php endif ?>
          <form method="POST" action="login.php">
            <div class="mb-3 row">
              <label for="email" class="col-md-4 col-form-label text-md-end">Email</label>
              <div class="col-md-6">
                <input id="email" type="email" class="form-control" name="email" autocomplete="email" autofocus>
              </div>
            </div>

            <div class="mb-3 row">
              <label for="password" class="col-md-4 col-form-label text-md-end">Password</label>
              <div class="col-md-6">
                <input id="password" type="password" class="form-control" name="password" autocomplete="password" autofocus>
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
