<?php

require "database.php";

session_start();

if (!isset($_SESSION["user"])) {
  header("Location: login.php");
  return;
}

$id = $_GET["id"];

$statement = $conn->prepare("SELECT * FROM contacts WHERE id = :id");
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

// Eliminar el contacto de la base de datos
$conn->prepare("DELETE FROM contacts WHERE id = :id")->execute([":id" => $id]);

$_SESSION["flash"] = ["message" => "Contact {$contact['name']} deleted."];

header("Location: home.php");



// Aquí están los comentarios para cada sección del código:

    // require "database.php";: Esta línea incluye el archivo "database.php", que se supone que contiene la configuración y la conexión a la base de datos.

    // session_start();: Esta función se utiliza para iniciar la sesión y permitir el acceso a la variable $_SESSION.

    // if (!isset($_SESSION["user"])) {: Verifica si el usuario ha iniciado sesión. Si no ha iniciado sesión, se redirige al usuario a la página de inicio de sesión utilizando header("Location: login.php"); y se detiene la ejecución del resto del código utilizando return;.

    // $id = $_GET["id"];: Obtiene el ID del contacto de la URL utilizando $_GET.

    // $statement = $conn->prepare("SELECT * FROM contacts WHERE id = :id");: Prepara una consulta para seleccionar el contacto correspondiente al ID proporcionado.

    // $statement->execute([":id" => $id]);: Ejecuta la consulta y pasa el valor del ID como un parámetro.

    // if ($statement->rowCount() == 0) {: Verifica si no se encontró ningún contacto con el ID proporcionado. Si es así, se envía un código de respuesta HTTP 404 y se muestra un mensaje "HTTP 404 NOT FOUND".

    // $contact = $statement->fetch(PDO::FETCH_ASSOC);: Recupera el contacto de la base de datos utilizando el método fetch() y lo almacena en la variable $contact.

    // if ($contact["user_id"] !== $_SESSION["user"]["id"]) {: Verifica si el ID de usuario del contacto no coincide con el ID de usuario almacenado en la sesión. Si no coinciden, se envía un código de respuesta HTTP 403 y se muestra un mensaje "HTTP 403 UNAUTHORIZED".

    // $conn->prepare("DELETE FROM contacts WHERE id = :id")->execute([":id" => $id]);: Prepara una consulta para eliminar el contacto correspondiente al ID proporcionado y ejecuta la consulta.

    // $_SESSION["flash"] = ["message" => "Contact {$contact['name']} deleted."];: Almacena un mensaje flash en la sesión para mostrarlo en la página de inicio.

    // header("Location: home.php");: Redirige al usuario a la página de inicio después de eliminar el contacto.
