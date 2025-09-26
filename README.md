# Proyecto Laravel con Breeze y Roles

## Requisitos
- PHP 8.2+
- Composer
- Node.js y npm
- MySQL o MariaDB

## Instalación

1. Clonar el repositorio
   ```bash
   git clone https://github.com/belen-valenzuela99/padelgo.git
   cd tu-repo

2. Instalar dependencias de PHP
   
       composer install

4. Instalar dependencias de Node

       npm install

5. Configurar variables de entorno
   
       cp .env.example .env

Editar el archivo .env con los datos de la base de datos.
6. Generar la clave de la aplicación

        php artisan key:generate

La base de datos esta en el archivo /database/appgestion.sql, se exporta en phpmyadmin y ya contiene dos usuarios
Los usuarios son:

    admin@gmail.com  => user Admin
    12345678
y

    jugador@gmail.com
    12345678

7. Ejecutar migraciones
   
       php artisan migrate 

9. Ejecutar el servidor

En una terminal:

    php artisan serve


En otra terminal:

    npm run dev


El proyecto estará disponible en:

    http://127.0.0.1:8000
