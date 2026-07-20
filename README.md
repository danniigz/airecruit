# AIRecruit — Career Copilot

TFM (Trabajo Fin de Máster) del Máster de Desarrollo con IA.

## Índice

- [Descripción general](#descripción-general)
- [Stack tecnológico](#stack-tecnológico)
- [Instalación y ejecución en local](#instalación-y-ejecución-en-local)
- [Estructura del proyecto](#estructura-del-proyecto)
- [Funcionalidades principales](#funcionalidades-principales)
- [Usuario de prueba](#usuario-de-prueba)
- [Despliegue](#despliegue)
- [Notas técnicas destacables](#notas-técnicas-destacables)
- [Enlaces](#enlaces)

---

## Descripción general

**AIRecruit** es una plataforma web que acompaña al usuario en su búsqueda de empleo: le ayuda a construir y analizar su CV, a comparar ofertas de trabajo con su perfil profesional obteniendo una puntuación de compatibilidad, y a generar cartas de presentación adaptadas a cada oferta.

El proyecto nace como TFM del Máster de Desarrollo con IA, y su premisa de diseño es que **la IA es el núcleo funcional de la aplicación, no un añadido superficial**. Cada feature de IA resuelve un problema real de análisis o generación de contenido, no es una llamada decorativa a un LLM:

| Feature de IA | Qué hace |
|---|---|
| **Análisis de CV** | Extrae el texto del PDF subido y lo envía a un LLM, que devuelve resumen, puntos fuertes, áreas de mejora, años de experiencia aproximados y skills principales detectadas. |
| **Scoring de compatibilidad** | Compara el CV con una oferta de empleo y devuelve una puntuación (0-100) junto con fortalezas, carencias y recomendaciones concretas para la candidatura. |
| **Generación de carta de presentación** | Redacta una carta personalizada a partir del perfil profesional del usuario y una oferta concreta, coherente con la experiencia real del candidato. |

---

## Stack tecnológico

| Capa | Tecnología |
|---|---|
| Backend + Frontend | Laravel (PHP 8.4), monolito con plantillas Blade |
| Estilos | TailwindCSS |
| Interactividad frontend | JavaScript vanilla + fetch API (sin Alpine.js ni otros frameworks JS) |
| Base de datos | SQLite (desarrollo) · PostgreSQL (producción) |
| IA | OpenAI API vía [`openai-php/client`](https://github.com/openai-php/client) |
| Extracción de texto de PDF | `smalot/pdfparser` |
| Testing | PHPUnit |
| Despliegue | [Railway](https://railway.app) |

---

## Instalación y ejecución en local

### Requisitos previos

- PHP 8.4 y Composer.
- Node.js y npm.
- Una **API key propia de OpenAI** — las funciones de IA (análisis de CV, scoring de compatibilidad, generación de cartas) no funcionarán sin ella.

### Pasos

```bash
# 1. Clonar el repositorio
git clone https://github.com/danniigz/airecruit.git
cd AIRecruit

# 2. Instalar dependencias PHP
composer install

# 3. Instalar dependencias JS
npm install

# 4. Configurar el entorno
cp .env.example .env
php artisan key:generate
```

Edita el `.env` y añade tu clave de OpenAI:

```env
OPENAI_API_KEY=sk-...
OPENAI_TIMEOUT=30
```

```bash
# 5. Crear la base de datos SQLite (si no existe) y migrar con datos de ejemplo
touch database/database.sqlite
php artisan migrate --seed

# 6. Compilar los assets
npm run dev       # modo desarrollo (watch), mientras trabajas en el frontend
# o bien
npm run build      # build final, para servir en producción o si no vas a tocar más el frontend

# 7. Levantar el servidor
php artisan serve
```

La aplicación quedará disponible en `http://localhost:8000` (o en la URL que gestione [Laravel Herd](https://herd.laravel.com/) si lo usas en su lugar).

---

## Estructura del proyecto

```
app/
├── Models/                    User, Profile, Experience, Education, Skill,
│                               Language, Certification, Cv, JobOffer,
│                               Comparison, CoverLetter
├── Http/Controllers/           Agrupados por dominio: Auth, Profile, Cv,
│                               JobOffer, Comparison, CoverLetter, Api, Dashboard
└── Services/
    ├── OpenAIService.php        Cliente base sobre openai-php/client
    │                             (modelo, manejo de errores, respuestas JSON)
    ├── CvAnalysisService.php     Extracción de texto del PDF y análisis de CV con IA
    ├── ComparisonService.php     Scoring de compatibilidad CV ↔ oferta de empleo
    └── CoverLetterService.php    Generación de cartas de presentación con IA

database/
├── migrations/                Una migración por tabla, ordenadas según dependencias (FKs)
└── seeders/                    DatabaseSeeder con usuario y datos de demo realistas

resources/
└── views/                      Plantillas Blade: perfil, CVs, ofertas, comparaciones,
                                 cartas de presentación, dashboard, auth
```

---

## Funcionalidades principales

MVP completo, en orden de prioridad:

| # | Funcionalidad | Descripción |
|---|---|---|
| 1 | **Autenticación** | Registro, login y logout |
| 2 | **Perfil profesional** | Gestión de experiencia laboral, educación, skills, idiomas y certificaciones |
| 3 | **CV + análisis con IA** | Subida de CV en PDF con extracción de datos clave, resumen, puntos fuertes y áreas de mejora |
| 4 | **Comparación con oferta** | Scoring de compatibilidad generado por IA con fortalezas, carencias y recomendaciones |
| 5 | **Carta de presentación con IA** | Generación a partir del perfil y una oferta concreta |
| 6 | **Dashboard** | Resumen de CVs, comparaciones y cartas generadas |

---

## Usuario de prueba

La base de datos sembrada (`php artisan migrate --seed`) incluye un usuario demo con datos de ejemplo ya cargados: perfil completo (experiencia, educación, skills, idiomas, certificaciones), CVs con análisis de IA, ofertas de empleo, comparaciones de compatibilidad y cartas de presentación generadas.

```
Email:    demo@airecruit.test
Password: password
```

---

## Despliegue

La aplicación está desplegada en Railway:

**https://airecruit-production.up.railway.app**

En producción se utiliza **PostgreSQL** en lugar de SQLite, y el esquema de URL se fuerza a HTTPS (`URL::forceScheme('https')` en `AppServiceProvider`) para evitar contenido mixto detrás del proxy de Railway.

---

## Notas técnicas destacables

Durante una revisión de QA del proyecto se detectó y corrigió un problema de seguridad: la descarga de CVs comprobaba la propiedad (*ownership*) del archivo a nivel de aplicación, pero el disco `local` de Laravel tenía `serve => true` en `config/filesystems.php`, lo que permitía servir el PDF directamente a través de la ruta de archivos del framework **sin pasar por esa comprobación de ownership**. La corrección consistió en poner `serve => false` en el disco `local`, forzando que toda descarga de CVs pase obligatoriamente por el controlador y su verificación de propiedad.

---

## Enlaces

- Slides: [PENDIENTE]
- Vídeo de presentación: [PENDIENTE]
