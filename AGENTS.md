# AGENTS.md — Laravel AI Image Gallery (`AI Dyslexic`)

Guía operativa para agentes y desarrolladores que trabajen en este repositorio.

## Stack y entorno
- **Backend:** PHP `>= 8.4`, Laravel `^13.0`, Laravel Sanctum `^4.0`, Intervention Image Laravel `^4.1` (WebP).
- **Base de datos:** PostgreSQL (`pdo_pgsql`).
- **Frontend:** Blade, Bootstrap 5 (`public/theme/`), Fancybox, CSS propio (`public/css/`).
- **Tests y formato:** PHPUnit `^13.0` (`php artisan test`), Laravel Pint (`./vendor/bin/pint`), PHPStan / Larastan (`composer phpstan`, nivel 5 en `phpstan.neon`).

## Estructura real del repositorio
```text
.
├── app/
│   ├── Console/Commands/        # CreateApiToken (api:token), GenerateSitemap (sitemap:generate)
│   ├── Exceptions/
│   ├── Http/
│   │   ├── Controllers/         # IndexController, CollectionController, PageController, WebhookController, Api/, Auth/
│   │   ├── Middleware/          # SecurityHeaders, TrustHosts, ...
│   │   ├── Requests/            # Api/, Auth/
│   │   └── Resources/           # UserResource
│   ├── Models/                  # Collection, CollectionRole, Image, Role, User
│   ├── Policies/                # CollectionPolicy
│   ├── Providers/
│   └── Services/                # ImageStorageService
├── bootstrap/
├── config/
├── database/
│   ├── factories/
│   ├── migrations/
│   └── seeders/                 # DatabaseSeeder, CollectionRoleSeeder, RoleSeeder
├── docs/
│   ├── deploys/                 # Guías de despliegue
│   └── info/                    # Documentación técnica VIVA del proyecto
├── lang/
├── public/
│   ├── css/                     # Estilos propios (styles.css, collection_blocks.css, collection_show.css, pages/)
│   ├── images/
│   ├── js/
│   └── theme/                   # Bootstrap 5, Fancybox, fuentes y scripts del tema
├── resources/
│   └── views/                   # auth/, collections/, components/, errors/, layouts/, pages/, vendor/
├── routes/                      # web.php, api.php (prefijo /api/v1), auth.php, channels.php, console.php
├── scripts/                     # exist_db_or_create.sh, functions.sh, generate_env.sh, webhooks/api-deploy.sh
├── storage/
└── tests/
    ├── Feature/                 # ApiCollectionTest, CollectionTest, ErrorPagesTest, SitemapCommandTest, Auth/
    └── Unit/                    # CollectionModelTest
```

## Trampas conocidas
| Área | Trampa | Detalle |
| --- | --- | --- |
| API | Prefijo `/api/v1` | `RouteServiceProvider` monta `routes/api.php` bajo `/api/v1`, no `/api`. |
| BD | `ILIKE` de PostgreSQL | `Collection::getQueryCollection()` usa `ILIKE`; requiere PostgreSQL. |
| BD | Esquemas legacy 2023 sin `role_id` | La migración `2026_11_23_000000_ensure_legacy_role_columns_exist` añade `users.role_id` y `collections.collection_role_id` si faltan antes de indexar; los usuarios existentes quedan con `role_id = NULL` hasta asignar el rol `admin`. |
| Auth | Rutas Breeze minimizadas | En `routes/auth.php` solo existen `login` y `logout`; el paquete `laravel/breeze` y el resto de scaffolding no utilizado fueron eliminados. |
| Permisos | `404` en lugar de `403` | `CollectionController::delete` y `deleteImage` devuelven `404` si el usuario no es `isAdmin`. |
| Home AJAX | Desfase de página | `home.blade.php` carga 24 elementos iniciales y el scroll infinito pide la página `2 + currentPage` de 8 en 8. |
| Sitemap | `APP_URL` en CLI/cron | `sitemap:generate` usa `APP_URL` para todas las URLs; si no está bien configurado, generará `http://localhost`. |
| Páginas | `active => false` | Las páginas con `active => false` quedan fuera del sitemap, pero siguen devolviendo HTTP `200` por URL directa. |

---

## Documentación

Protocolo permanente de documentación técnica del repositorio.

### Estructura de `docs/`
- `docs/info/` — documentación técnica VIVA del proyecto (un `.md` por módulo + guías transversales).
- `docs/apis/<api>/` — documentación oficial destilada y verificada de terceros (solo crear cuando el repositorio integre una API externa real).
- `docs/planning/` — **[NO GIT · EFÍMERO · POR DESARROLLADOR]** fases `<NN>-<fase>.md`, `dudas/`, `archived/`.
- `docs/auditorias/` — **[NO GIT · EFÍMERO · POR DESARROLLADOR]** informes de auditoría.
- `docs/future/` — decisiones aplazadas (no bugs ni deuda técnica).
- `docs/deploys/` — guías de despliegue.

### Índice de `docs/info/`
- [`docs/info/README.md`](docs/info/README.md) — índice maestro.
- [`docs/info/collections.md`](docs/info/collections.md) — galería pública, búsqueda, AJAX y borrado admin.
- [`docs/info/api-collections.md`](docs/info/api-collections.md) — API REST v1 (`/api/v1/collections/*`) y almacenamiento WebP.
- [`docs/info/pages.md`](docs/info/pages.md) — páginas estáticas (`/page/{page}`).
- [`docs/info/auth.md`](docs/info/auth.md) — autenticación web, roles, Sanctum y rutas auxiliares.
- [`docs/info/sitemap.md`](docs/info/sitemap.md) — comando `sitemap:generate` y scheduler diario `03:42`.
- [`docs/info/DESIGN.md`](docs/info/DESIGN.md) — estilos, Bootstrap 5, tema claro/oscuro y SEO.
- [`docs/info/COMPONENTS.md`](docs/info/COMPONENTS.md) — layouts, vistas parciales y componentes Blade.
- [`docs/info/commands.md`](docs/info/commands.md) — comandos Artisan, Composer, tests y `scripts/`.
- [`docs/info/decisiones-tecnicas.md`](docs/info/decisiones-tecnicas.md) — decisiones deliberadas de arquitectura.
- [`docs/info/_MODULE_TEMPLATE.md`](docs/info/_MODULE_TEMPLATE.md) — plantilla para módulos nuevos.

### Reglas permanentes
1. **Documentar es parte de la tarea:** ninguna tarea termina sin actualizar su documentación **en el mismo commit** que el código.
2. **Jerarquía de verdad:** código > `docs/info/` > `AGENTS.md` > el resto. `docs/planning/`, `docs/future/` y `docs/auditorias/` nunca son fuente de verdad del estado actual.
3. **Discrepancias:** si la documentación contradice al código, se corrige en el commit en que se detecta.
4. **Ciclo de módulos:** tocas un módulo → actualizas su `.md`. Creas uno → desde `docs/info/_MODULE_TEMPLATE.md` e indexado en `docs/info/README.md` y `AGENTS.md`. Eliminas uno → borras su `.md` y lo quitas de todos los índices.
5. **Pie de fecha obligatorio:** todo archivo bajo `docs/` termina con separador `---` y la línea exacta: `> Creado: YYYY-MM-DD · Última revisión: YYYY-MM-DD`. La creación no se modifica; la revisión se actualiza en cada cambio.
6. **Checklists verificados:** `[x]` significa verificado funcionando y cumpliendo, no solo código escrito.
7. **Trabajo efímero:** `docs/planning/` y `docs/auditorias/` permanecen siempre en `.gitignore`. Ciclo: crear → trabajar → verificar → promocionar lo duradero → borrar. Ningún archivo versionado puede enlazar a ellos.
8. **Promoción antes de borrar lo efímero:** comportamiento de módulo → `docs/info/<modulo>.md`; decisión deliberada → `docs/info/decisiones-tecnicas.md`; trampa duradera → tabla de trampas de `AGENTS.md`; cambio de estructura/comandos → `AGENTS.md` / `docs/info/commands.md`; idea aplazada → `docs/future/`; regresión → test.
9. **Lectura dirigida:** al trabajar en un módulo lee solo su `.md`; si tocas frontend añade `DESIGN.md` y `COMPONENTS.md`; si tocas una API de terceros lee `docs/apis/<api>/` en orden (`README.md` → `00-fundamentos.md` + `ERRATAS.md` + `LIMITACIONES.md` → dominio necesario). No leas el resto por rutina.
10. **APIs externas:** nunca configurar nada desde una especificación oficial sin verificarlo con una petición real; lo no comprobado se marca `⚠️ sin verificar`.
11. **Idioma:** documentación, comentarios y textos de usuario en español; identificadores, nombres de fichero/directorio y logs en inglés (salvo campos devueltos por APIs externas).
12. **Privacidad y atribución:** nick `@raupulus`, único correo público permitido `public@raupulus.dev`. Nunca utilizar ningún correo personal o privado del creador. Sin firmas de agente en commits, PRs ni documentación.
13. **Commits atómicos:** cada commit agrupa un único cambio lógico coherente (junto con su documentación y tests asociados), evitando mezclar refactors, correcciones o funcionalidades no relacionadas en el mismo commit.

### Checklist antes de cerrar cualquier tarea
- [ ] Documentación del módulo tocado actualizada en el mismo commit
- [ ] Fechas de «Última revisión» al día en los archivos bajo `docs/` tocados
- [ ] Módulos nuevos indexados en `docs/info/README.md` y `AGENTS.md`
- [ ] Módulos eliminados fuera de todos los índices
- [ ] Ningún archivo versionado enlaza a `docs/planning/` ni a `docs/auditorias/`
- [ ] `/docs/planning/` y `/docs/auditorias/` siguen en `.gitignore`
- [ ] Lo duradero de planificaciones o auditorías cerradas promocionado y el archivo borrado
- [ ] Checklists `[x]` verificados, no asumidos
- [ ] Árbol de estructura de `AGENTS.md` refleja los directorios reales
