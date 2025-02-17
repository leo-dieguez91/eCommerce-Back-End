# Backend eCommerce Laravel

Backend API RESTful desarrollado con Laravel para un sistema de eCommerce.

## Requisitos Previos

Asegúrate de tener instalados los siguientes componentes antes de continuar:

- **PHP** >= 8.1
- **Composer** (Gestor de dependencias de PHP)
- **Docker Desktop** (Para manejo de contenedores)
- **Git** (Control de versiones)

## Instalación

Sigue estos pasos para configurar el proyecto en tu entorno local:

1. **Clona el repositorio**:

   ```bash
   git clone https://github.com/leo-dieguez91/eCommerce-Back-End.git

Ingresa al directorio del proyecto:

  
    cd eCommerce-Back-End 
Ejecuta el script de instalación:

    chmod +x scripts/install.sh;
    ./scripts/install.sh

**El script realizará automáticamente las siguientes tareas:**

* Copiar el archivo de configuración .env.
* Instalar Laravel Sail.
* Iniciar los contenedores Docker (MySQL, Redis, Mailpit).
* Generar la clave de la aplicación.
* Ejecutar las migraciones y seeders.
* Configurar Passport para la autenticación.

--------
**Servicios Disponibles:**

_Una vez instalado, tendrás acceso a los siguientes servicios:_

* API: http://localhost

* MySQL: Puerto 3306

* Redis: Puerto 6379

* Mailpit: http://localhost:8025 (Interfaz para ver correos enviados)

------
**Acceso de Administrador:**

_Para acceder como administrador y gestionar los productos, utiliza las siguientes credenciales:_

**Email**: admin@admin.com

**Contraseña**: admin

--------
**Comandos Útiles:**

_Aquí tienes algunos comandos útiles para manejar el proyecto:_

Iniciar los contenedores:
``` 
    ./vendor/bin/sail up -d
```
Detener los contenedores:
```
    ./vendor/bin/sail down
```
Ejecutar tests:
```
    ./vendor/bin/sail test
```
Acceder a la base de datos:
```
    ./vendor/bin/sail mysql
```
------
**Documentación:**

_La documentación de la API está disponible en:_
```
    Swagger UI: http://localhost/api/documentation
```
--------
**Pruebas con Postman**

Para probar la API, puedes descargar la colección de Postman:

**_Collection:_**

[![Download Postman Collection](https://run.pstmn.io/button.svg)](https://github.com/leo-dieguez91/eCommerce-Back-End/raw/main/postman/eCommerce.postman_collection.json)
- Descarga la colección haciendo clic en el botón anterior.

**_Environment:_**

[![Config Enviroment LocalHost](https://img.shields.io/badge/Probar_en-Postman-orange)](https://github.com/leo-dieguez91/eCommerce-Back-End/raw/main/postman/localhost.postman_environment.json)
- Configura el entorno Local de Postman con el archivo descargado.
