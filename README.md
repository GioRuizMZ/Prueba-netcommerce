# Prueba Netcommerce

API en Laravel 10 para consultar empresas con sus tareas y crear tareas asignadas a un usuario.

## Requisitos

- PHP 8.1 o superior
- Composer
- MySQL o MariaDB

## Instalación

```bash
git clone https://github.com/GioRuizMZ/Prueba-netcommerce.git
cd Prueba-netcommerce
composer install
cp .env.example .env
php artisan key:generate
```

Crear la base de datos `netcommerce` y ajustar las credenciales en `.env` si es necesario:

```
DB_DATABASE=netcommerce
DB_USERNAME=root
DB_PASSWORD=
```

Crear las tablas y cargar datos de prueba:

```bash
php artisan migrate --seed
php artisan serve
```

La API queda disponible en `http://127.0.0.1:8000/api`.

## Endpoints

### GET /api/companies

Lista las empresas con sus tareas. Acepta el parámetro opcional `search` para filtrar por nombre de empresa.

```
GET /api/companies
GET /api/companies?search=net
```

### POST /api/tasks/create

Crea una tarea para un usuario dentro de una empresa.

```json
{
    "company_id": 1,
    "name": "task 4",
    "description": "task content",
    "user_id": 1
}
```

Respuesta `201`:

```json
{
    "id": 4,
    "name": "task 4",
    "description": "task content",
    "user": "Akira",
    "company": {
        "id": 1,
        "name": "Netcommerce"
    }
}
```

Si la validación falla responde `422` con los errores. Un usuario no puede tener más de 5 tareas pendientes; esa validación está en la regla `App\Rules\MaxPendingTasks`.

## Consideraciones

- Las rutas usan el prefijo `/api` que Laravel asigna a `routes/api.php`.
- El parámetro `search` busca coincidencias parciales en el nombre de la empresa. Si no se envía, se devuelven todas.
- Al crear una tarea, `start_at` se asigna con la fecha actual, `expired_at` queda en `null` y la tarea inicia como pendiente.
- `description` es opcional.
- El seeder crea la empresa Netcommerce y el usuario Akira para que las respuestas coincidan con los ejemplos del documento.

## Pruebas

Las pruebas usan SQLite en memoria, no modifican la base de datos local.

```bash
php artisan test
```

## Capturas

Listado de empresas sin parámetro:

![GET sin parámetro](capturas/Get%20sin%20parametro.png)

Listado de empresas filtrando por nombre:

![GET con parámetro](capturas/Get%20con%20parametro.png)

Creación de una tarea:

![POST crear tarea](capturas/Post%20para%20crear%20tareas.png)

Validación con body vacío:

![POST sin body](capturas/Post%20sin%20body.png)

Usuario con 5 tareas pendientes:

![POST con 5 tareas pendientes](capturas/Post%20con%205%20tareas%20pendientes.png)
