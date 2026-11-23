# Módulo: `auth` (Autenticación, usuarios, roles y rutas auxiliares)

## Qué hace y qué NO hace
- **Hace:**
  - Gestiona inicio y cierre de sesión web mediante Laravel Breeze (`login`, `logout`).
  - Gestiona usuarios y roles (`User`, `Role`) y centraliza la autorización en `App\Policies\CollectionPolicy`.
  - Protege `User::$fillable` excluyendo `role_id` para evitar escalada de privilegios por asignación masiva.
  - Soporta tokens personales de API con Laravel Sanctum (`HasApiTokens` en `User`) emitidos por CLI mediante `php artisan api:token {email?} {--name=general}` con scope `collections:write` y expiración configurable (`SANCTUM_TOKEN_EXPIRATION`).
  - Mantiene el endpoint stub `/webhooks/twitter` mediante `WebhookController@twitter`.
- **NO hace:**
  - No permite registro público, recuperación de contraseña, verificación de email ni confirmación/cambio de contraseña por web (`routes/auth.php` solo define `login` y `logout`).
  - No expone ningún endpoint web para emitir tokens Sanctum (la ruta `/tokens/create` fue eliminada).

## Modelo de datos
- **`roles`** (`App\Models\Role`): `id`, `slug` (`unique`), `name`, `show_name`, `description`.
  - Seeder: `Database\Seeders\RoleSeeder` crea el rol `admin`.
- **`users`** (`App\Models\User`): `id`, `role_id` (FK nullable a `roles.id`, `ON DELETE SET NULL`, indexado), `name`, `email` (unique), `email_verified_at`, `password`, `remember_token`.
  - Accessors:
    - `isAdmin`: `true` únicamente si la relación `role` tiene `slug === 'admin'` (comprobado sin lazy loading).
    - `canDelete`: alias de `$this->isAdmin`.
- **`personal_access_tokens`**: tabla estándar de Sanctum (por defecto expiran a los `SANCTUM_TOKEN_EXPIRATION` minutos, `525600` = 1 año).

## Flujos principales
1. **Login / Logout web (`AuthenticatedSessionController`):**
   - `GET /login` muestra el formulario; `POST /login` autentica con `LoginRequest` y redirige a `RouteServiceProvider::HOME` (`/`).
   - `POST /logout` cierra sesión y redirige a `/`.
2. **Emisión de tokens API (`CreateApiToken`):**
   - `php artisan api:token {email?} {--name=general}` verifica que el usuario tenga rol `admin` (`isAdmin`) y emite un token Sanctum con capacidad `['collections:write']`.

## Puntos de entrada
| Método | Ruta | Nombre | Auth / Permisos | Límites / Notas |
| --- | --- | --- | --- | --- |
| `GET` | `/login` | `login` | `guest` | Formulario Breeze |
| `POST` | `/login` | — | `guest` | Rate limit de Breeze en `LoginRequest` |
| `POST` | `/logout` | `logout` | `auth` | Cierra sesión web |
| `ANY` | `/webhooks/twitter` | `webhooks.twitter` | Pública | Stub en `WebhookController@twitter` que devuelve `{"success": true}` |

## Dependencias
- **Depende de:** `laravel/breeze`, `laravel/sanctum`, `RoleSeeder`, `CollectionPolicy`.
- **Es usado por:** `collections` (autorización `CollectionPolicy::delete` para borrado web) y `api-collections` (`auth:sanctum` + `CollectionPolicy` + ability `collections:write`).

## Configuración
| Variable / Clave | Valor por defecto | Efecto |
| --- | --- | --- |
| `SANCTUM_TOKEN_EXPIRATION` | `525600` (minutos) | Caducidad de tokens personales Sanctum en `config/sanctum.php` |
| `RouteServiceProvider::HOME` | `/` | Destino tras login |

## Trampas conocidas
- `User::getIsAdminAttribute()` exige un registro `Role` con `slug === 'admin'` (ya no acepta `role_id === 1` sin verificar el `slug`).
- En bases de datos antiguas creadas antes de noviembre de 2023 (`2014_10_12_000000_create_users_table` antes de añadir `role_id` in-place), la migración idempotente `2026_11_23_000000_ensure_legacy_role_columns_exist` añade `users.role_id` (y FK) antes de crear `users_role_id_idx` en `2026_11_23_000001`; tras migrar un entorno legacy conviene ejecutar `RoleSeeder` y asignar el rol `admin` al usuario administrador.

## Tests que lo cubren
- `tests/Feature/Auth/AuthenticationTest.php` (login/logout).
- `tests/Feature/CollectionTest.php` (verificación de `isAdmin` / `CollectionPolicy` en borrado).
- `tests/Feature/ApiCollectionTest.php` (autenticación Sanctum, `CollectionPolicy`, abilities y comando `api:token`).

## Pendiente real
- Ninguno verificado en código.

---

> Creado: 2026-11-23 · Última revisión: 2026-11-23
