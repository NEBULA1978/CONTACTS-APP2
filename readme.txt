Crear carpeta sql y crear archivo setup.sql con contenido:

DROP DATABASE SI EXISTE contacts_app; CREAR BASE DE DATOS contactos_app; UTILIZAR contactos_aplicación; CREAR usuarios de TABLA (id INT AUTO_INCREMENT CLAVE PRINCIPAL, nombre VARCHAR (255), correo electrónico VARCHAR (255) ÚNICO, contraseña VARCHAR (255)); CREAR contactos de TABLA (id INT AUTO_INCREMENT PRIMARY KEY, nombre VARCHAR (255), user_id INT NOT NULL, phone_number VARCHAR (255), FOREIGN KEY (user_id) REFERENCIAS usuarios (id));

Iniciar xampp en local: Vamos a carpeta: cd /opt/lampp/ sudo ./manager-linux-x64.run

Abrimos terminal bash: mysql -u root USE contactos_app;

Para iniciar APP login y registro de usuarios con phph8: localhost/CONTACTS-APP2
