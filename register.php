<?php

// Se incluye el archivo que contiene la configuración de la base de datos
require "database.php";

// Variable para almacenar el mensaje de error
$error = null;

// Verificar si la solicitud es de tipo POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Verificar si alguno de los campos está vacío
  if (empty($_POST["name"]) || empty($_POST["email"]) || empty($_POST["password"])) {
    $error = "Please fill all the fields."; // Por favor, completa todos los campos.
  } else if (!str_contains($_POST["email"], "@")) {
    $error = "Email format is incorrect."; // El formato del correo electrónico es incorrecto.
  } else {
    // Preparar y ejecutar una consulta para verificar si el correo electrónico ya existe en la base de datos
    $statement = $conn->prepare("SELECT * FROM users WHERE email = :email");
    $statement->bindParam(":email", $_POST["email"]);
    $statement->execute();

    // Verificar si se encontraron registros con el correo electrónico proporcionado
    if ($statement->rowCount() > 0) {
      $error = "This email is taken."; // Este correo electrónico ya está en uso.
    } else {
      // Insertar los datos del usuario en la base de datos
      $conn
        ->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)")
        ->execute([
          ":name" => $_POST["name"],
          ":email" => $_POST["email"],
          ":password" => password_hash($_POST["password"], PASSWORD_BCRYPT), // Se almacena una versión hash de la contraseña
        ]);

      // Redirigir al usuario a la página de inicio después del registro exitoso
      header("Location: home.php");
    }
  }
}
?>

<?php require "partials/header.php" ?>

<div class="container pt-5">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header">Register</div>
        <div class="card-body">
          <?php if ($error) : ?>
            <p class="text-danger">
              <?= $error ?>
            </p>
          <?php endif ?>
          <form method="POST" action="register.php">
            <div class="mb-3 row">
              <label for="name" class="col-md-4 col-form-label text-md-end">Name</label>
              <div class="col-md-6">
                <input id="name" type="text" class="form-control" name="name" autocomplete="name" autofocus>
              </div>
            </div>

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

<!-- APUNTES -->
<!-- Aquí hay una explicación de las partes principales del código:

La línea require "database.php"; se utiliza para incluir el archivo database.php, que se supone que contiene la configuración y la conexión a la base de datos.

El código verifica si la solicitud es de tipo POST utilizando $_SERVER["REQUEST_METHOD"] == "POST". Esto se hace para asegurarse de que el formulario se envió mediante el método POST.

Luego, se realizan varias comprobaciones para validar los campos del formulario:
Verifica si alguno de los campos (name, email o password) está vacío. Si es así, se establece el mensaje de error en la variable $error.
Utiliza la función str_contains() para verificar si el campo de correo electrónico contiene el símbolo "@" como una forma básica de validación de formato. Si no contiene "@", se establece el mensaje de error correspondiente.

Si no se encontraron errores en las comprobaciones anteriores, se realiza una consulta a la base de datos para verificar si el correo electrónico ya está registrado. Si se encuentra un registro con el correo electrónico proporcionado, se establece el mensaje de error correspondiente.

Si no se encontraron errores hasta este punto, se insertan los datos del usuario en la base de datos utilizando una declaración preparada. La contraseña se guarda en forma de hash utilizando la función password_hash() para mejorar la seguridad.

Después de un registro exitoso, se redirige al usuario a la página "home.php" utilizando header("Location: home.php").

El resto del código HTML es la estructura básica del formulario de registro, que muestra cualquier mensaje de error y contiene los campos de nombre, correo electrónico y contraseña. -->
