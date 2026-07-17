# CLAUDE.md — AIRecruit (Career Copilot)

Este archivo se carga automáticamente por Claude Code al inicio de cada sesión. Contiene el contexto y las reglas del proyecto. Sigue estas instrucciones de forma estricta.

## Contexto del proyecto

AIRecruit es un TFM (Trabajo Fin de Máster) del Máster de Desarrollo con IA. Es una plataforma web que acompaña al usuario en su búsqueda de empleo: creación y análisis de CV, comparación de ofertas con puntuación de compatibilidad generada por IA, y generación de cartas de presentación con IA.

**Requisito clave del tribunal:** la IA debe ser el núcleo funcional del proyecto, no un añadido superficial. Cada feature relacionada con IA debe demostrar un uso real y justificado (análisis de texto, extracción de datos, scoring, generación de contenido), no una simple llamada decorativa a un LLM.

**Deadline de entrega: 20/07/2026.** El tiempo es muy limitado — prioriza siempre robustez y funcionalidad sobre elegancia o exhaustividad.

## Stack tecnológico (NO te desvíes de esto)

- **Backend + Frontend:** Laravel (PHP), monolito con Blade templates. NO usar React, Vue, Livewire, ni Inertia.
- **Estilos:** TailwindCSS.
- **Interactividad en frontend:** JavaScript vanilla + fetch API. NO usar Alpine.js ni ningún framework JS adicional.
- **Base de datos:** SQLite en desarrollo (ya configurado). No migrar a MySQL/PostgreSQL salvo que se pida explícitamente.
- **IA:** OpenAI API a través del paquete `openai-php/client`.
- **Almacenamiento de archivos (PDFs de CV):** almacenamiento local de Laravel (`storage/app`), usando el disco `public` o `local` según corresponda.
- **Testing:** el que se haya elegido al crear el proyecto (Pest o PHPUnit) — usar el mismo en todo el proyecto, no mezclar.
- **CI/CD y despliegue:** fuera del scope de desarrollo diario; no configurar Docker ni GitHub Actions salvo petición explícita.

## Convenciones del proyecto

- Sigue las convenciones estándar de Laravel: controladores en `app/Http/Controllers`, form requests para validación compleja, resources/policies solo si aportan valor real (no sobre-ingeniería).
- Nombrado en inglés para código (variables, clases, métodos, tablas, columnas); los textos visibles al usuario (vistas Blade) en español.
- Usar Eloquent ORM, evitar queries SQL crudas salvo necesidad justificada.
- Migraciones: una por tabla, con nombres descriptivos y timestamps correctos según el orden de dependencias (FKs).
- Rutas agrupadas por dominio en `routes/web.php` (o `routes/api.php` si se separa lógica de fetch), con nombres de ruta (`->name()`) siempre definidos.
- Commits pequeños y descriptivos en español o inglés, consistente.

## Modelo de datos (entidades principales)

- `users` — autenticación estándar de Laravel.
- `profiles` — perfil profesional del usuario (datos ampliados).
- `experiences` — experiencia laboral.
- `educations` — formación académica.
- `skills` — habilidades (probablemente relación muchos-a-muchos con users/profiles).
- `languages` — idiomas.
- `certifications` — certificaciones.
- `cvs` — CVs subidos/generados, con referencia al archivo PDF.
- `job_offers` — ofertas de empleo introducidas por el usuario (manual o pegadas).
- `comparisons` — comparación IA entre un CV/perfil y una oferta, con puntuación de compatibilidad.
- `cover_letters` — cartas de presentación generadas por IA.

Antes de crear o modificar migraciones, consulta el diagrama ER si existe duda sobre relaciones o claves foráneas.

## Alcance del MVP (ESTO ES CRÍTICO — lee dos veces)

### Incluido en el MVP (prioridad total, en este orden):
1. Autenticación (registro, login, logout — Laravel Breeze-style manual o auth scaffolding básico, sin starter kit).
2. Gestión de perfil profesional (crear/editar experiencia, educación, skills, idiomas, certificaciones).
3. Subida de CV (PDF) + análisis con IA (extracción de datos clave, resumen o feedback).
4. Comparación de oferta de empleo con el perfil/CV → scoring de compatibilidad generado por IA.
5. Generación de carta de presentación con IA a partir del perfil y una oferta.
6. Dashboard básico (resumen de CVs, comparaciones y cartas generadas).

### Fase 2 — NO IMPLEMENTAR salvo petición explícita (documentar como "work in progress" si se menciona):
- Simulador de entrevistas.
- Chat profesional / asistente conversacional.
- Roadmap de aprendizaje personalizado.
- Cualquier feature no listada explícitamente en el MVP de arriba.

**Regla de oro:** si una tarea no está en la lista de "Incluido en el MVP", no la implementes aunque parezca una mejora obvia o rápida. Pregunta primero o descarta la idea. El objetivo es tener el MVP completo y funcionando antes que features extra a medias.

## Comandos artisan habituales de este proyecto

```bash
php artisan serve                     # levantar servidor de desarrollo
php artisan migrate                   # aplicar migraciones
php artisan migrate:fresh --seed      # resetear BD y volver a sembrar
php artisan make:model NombreModelo -mfc   # modelo + migración + factory + controlador
php artisan make:controller NombreController
php artisan make:request NombreRequest
php artisan route:list                # ver todas las rutas registradas
php artisan tinker                    # consola interactiva para probar queries/modelos
```

## Notas finales

- Ante la duda entre "hacerlo bien" y "hacerlo funcionar dado el plazo", prioriza que funcione. Se puede refactorizar después si sobra tiempo.
- No introduzcas dependencias nuevas (paquetes Composer o npm) sin confirmarlo antes — cada dependencia nueva es tiempo de configuración que no sobra.
- Si una petición del usuario (Dani) contradice el alcance del MVP definido aquí, avísale explícitamente antes de implementarla.
