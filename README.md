# CRM_PHP
Sistema de Gestión de Relaciones con Clientes (CRM) desarrollado con Laravel, Vue.js y Ant Design.

Requisitos: PHP >= 8.1, Composer, Node.js >= 18 y npm o yarn, MySQL o MariaDB, Git, Servidor web (Apache / Nginx) para producción.

Instalación y Desarrollo:
1. Clonar el repositorio:
git clone https://github.com/Hebicr/CRM_PHP.git
cd CRM_PHP

2. Instalar dependencias:
composer install
npm install

3. Configurar entorno:
cp .env.example .env
Editar .env con los datos de tu base de datos:
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nombre_base_datos
DB_USERNAME=usuario
DB_PASSWORD=contraseña

4. Generar clave de Laravel:
php artisan key:generate

5. Ejecutar migraciones y seeders:
php artisan migrate --seed

6. Compilar assets:
npm run dev

7. Iniciar servidor de desarrollo:
php artisan serve
Acceder en: http://127.0.0.1:8000


/************************************************************************************/

Despliegue en Producción:
1. Instalar dependencias sin paquetes de desarrollo:
composer install --no-dev --optimize-autoloader
npm install

2. Configurar entorno de producción:
APP_ENV=production
APP_DEBUG=false
APP_URL=https://tu-dominio.com
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nombre_base_datos
DB_USERNAME=usuario
DB_PASSWORD=contraseña

3. Generar clave de Laravel:
php artisan key:generate

4. Ejecutar migraciones y seeders forzando producción:
php artisan migrate --seed --force

5. Compilar assets para producción:
npm run build

6. Optimizar Laravel:
php artisan config:cache
php artisan route:cache
php artisan view:cache

7. Configurar servidor web:
Apache: Apuntar DocumentRoot a la carpeta public/
Nginx: Configurar el root en public/ y redirigir todas las rutas a index.php

Comandos útiles:
Limpiar cache y recompilar configuración:
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

Ejecutar tests:
php artisan test

Compilación con watch (auto-recompila assets en desarrollo):
npm run watch

Tecnologías:
Backend: Laravel 10
Frontend: Vue 3
UI: Ant Design Vue
Base de datos: MySQL / MariaDB
Control de versiones: Git

Licencia:
MIT License
