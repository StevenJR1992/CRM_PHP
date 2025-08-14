## Starter Project Using Laravel Vue Ant

This is starter project for basic setup using laravel, vue and Ant. Following below steps for the setup

-   Create Modules Folder if not exists.
-   composer install
-   npm install
-   npm run watch


## Iniciar el proyecto en local

NOTA: El punto 3 y 4 se deben dejecutar en intancias de terminar diferentes.

1- Instalar composer (Se instala en la maquina y se ejuca en terminar del proyecto "composer install")
2- Instalar dependencias "npm install"
3- Se habilita el tunel ssh para la conexion de la BD ("ssh -L 3310:127.0.0.1:3306 root@3.220.173.150")
4- Se levanta el servicio local laravel ("php artisan serve ")
5- Se contruye el proyecto ("npm run build")
6- Se ejecuta ("npm run dev")