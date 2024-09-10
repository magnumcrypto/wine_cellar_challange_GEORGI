# Webservices - Wine Cellar
***

# DESCRIPTION
### English
This is a web services project to manage a wine cellar.

This project uses API RESTFULL.
### Español
Este es un proyecto de servicios web para gestionar una bodega de vinos.

Este proyecto utiliza API RESTFULL.
***

## INSTALATION

### English
1. Clone the project in your local machine **[REPOSITORY](https://github.com/magnumcrypto/wine_cellar_challange_GEORGI.git)**
2. Open the project in your favorite IDE
3. Open the terminal and run the comand `composer install`
4. Create a database in your local machine
5. ⚠️ ***Configure the `.env` file with your database credentials*** ⚠️
6. Run the command `symfony:serve` or `symfony:server:start`
### Español
1. Clona el proyecto en tu maquina local **[REPOSITORIO](https://github.com/magnumcrypto/wine_cellar_challange_GEORGI.git)**
2. Abre el proyecto en tu IDE favorito
3. Abre la terminal y ejecuta el comando `composer install`
4. Crea una base de datos en tu maquina local
5. ⚠️ ***Configura el archivo `.env` con tus credenciales de la base de datos*** ⚠️
6. Ejecuta el comando `symfony:serve` o `symfony:server:start`
***

## TESTING

### English
1. To run the tests, first you need to create a database for testing, for example `wine_cellar_test`
2. ⚠️ ***Configure the `.env.local` file with your database credentials*** ⚠️
3. Load the fixtures with the command `php bin/console hautelook:fixtures:load`
4. If you want to run a specific class you can run the command `php bin/phpunit --filter ClassNameTest` or for more details `php bin/phpunit --testdox --filter ClassNameTest `
5. If you want to run all the tests, you can run the command `php bin/phpunit`

### Español
1. Para ejecutar las pruebas, primero debes crear una base de datos para las pruebas, por ejemplo `wine_cellar_test`
2. ⚠️ ***Configura el archivo `.env.local` con tus credenciales de la base de datos*** ⚠️
3. Carga los fixtures con el comando `php bin/console hautelook:fixtures:load`
4. Si deseas ejecutar una clase específica puedes ejecutar el comando `php bin/phpunit --filter ClassNameTest` o para más detalles `php bin/phpunit --testdox --filter ClassNameTest `
5. Si deseas ejecutar todas las pruebas, puedes ejecutar el comando `php bin/phpunit`

***

## ENDPOINTS
### English
To get all documentation about the endpoints, you can access the route `/api/doc`

Example:
```
 http://localhost:8000/api/doc
```
### Español
Para obtener toda la documentación sobre los endpoints, puedes acceder a la ruta `/api/doc`

Ejemplo:
```
 http://localhost:8000/api/doc
```