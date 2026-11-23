# Módulo: `auth` (Autenticación, usuarios, roles y rutas auxiliares)

## Qué hace y qué NO hace
- **Hace:**
  - Gestiona inicio y cierre de sesión web mediante Laravel Breeze (`login`, `logout`).
  - Gestiona usuarios y roles (`User`, `Role`), determinando permisos de administración (`isAdmin`, `canDelete`).
  - Soporta tokens personales de API con Laravel Sanctum (`HasApiTokens` en `User`).
  - Expone en entorno local + debug un helper `/tokens/create` para emitir un token Sanctum del primer usuario.
  - Mantiene el endpoint stub `/webhooks/twitter` en `routes/web.php`.
- **NO hace:**
  - No permite registro público, recuperación de contraseña, verificación de email ni confirmación/cambio de contraseña por web: esas rutas están comentadas en `routes/auth.php` (aunque sus controladores y vistas Breeze existen en el repositorio).

## Modelo de datos
- **`roles`** (`App\Models\Role`): `id`, `slug`, `name`, `show_name`, `description`.
  - Seeder: `Database\Seeders\RoleSeeder` crea el rol `admin`.
- **`users`** (`App\Models\User`): `id`, `role_id` (FK nullable a `roles.id`, `ON DELETE SET NULL`), `name`, `email` (unique), `email_verified_at`, `password`, `remember_token`.
  - Accessors:
    - `isAdmin`: `true` si `(int) $this->role_id === 1 || $this->role?->slug === 'admin'`.
    - `canDelete`: alias de `$this->isAdmin`.
- **`personal_access_tokens`**: tabla estándar de Sanctum.

## Flujos principales
1. **Login / Logout web (`AuthenticatedSessionController`):**
   - `GET /login` muestra el formulario; `POST /login` autentica con `LoginRequest` y redirige a `RouteServiceProvider::HOME` (`/`).
   - `POST /logout` cierra sesión y redirige a `/`.
2. **Emisión de token en local (`/tokens/create`):**
   - Solo se registra si `app()->isLocal() && config('app.debug')`.
   - Toma `User::first()` (o `404` si no hay usuarios) y devuelve `['token' => $user->createToken('general')->plainTextToken]`.

## Puntos de entrada
| Método | Ruta | Nombre | Auth / Permisos | Límites / Notas |
| --- | --- | --- | --- | --- |
| `GET` | `/login` | `login` | `guest` | Formulario Breeze |
| `POST` | `/login` | — | `guest` | Rate limit de Breeze en `LoginRequest` |
| `POST` | `/logout` | `logout` | `auth` | Cierra sesión web |
| `GET` | `/tokens/create` | — | Pública (solo `local && debug`) | No registrada en producción |
| `ANY` | `/webhooks/twitter` | — | Pública | Stub que devuelve `{"success": true}` |

## Dependencias
- **Depende de:** `laravel/breeze`, `laravel/sanctum`, `RoleSeeder`.
- **Es usado por:** `collections` (autorización `isAdmin` para borrado web) y `api-collections` (`auth:sanctum`).

## Configuración
| Variable / Clave | Valor por defecto | Efecto |
| --- | --- | --- |
| `APP_ENV` / `APP_DEBUG` | `local` / `true` en `.env.example` | Habilita o deshabilita el registro de `/tokens/create` |
| `RouteServiceProvider::HOME` | `/` | Destino tras login |

## Trampas conocidas
- Existen tests de Breeze en `tests/Feature/Auth/` (`RegistrationTest`, `PasswordResetTest`, `EmailVerificationTest`, `PasswordConfirmationTest`, `PasswordUpdateTest`) para rutas que actualmente están comentadas en `routes/auth.php`.
- `User::getIsAdminAttribute()` considera admin tanto `role_id === 1` como `role->slug === 'admin'`.

## Tests que lo cubren
- `tests/Feature/Auth/AuthenticationTest.php` (login/logout).
- `tests/Feature/CollectionTest.php` (verificación de `isAdmin` en borrado).
- `tests/Feature/ApiCollectionTest.php` (autenticación Sanctum).

## Pendiente real
- Ninguno verificado en código.

---

> Creado: 2026-11-23 · Última revisión: 2026-11-23
