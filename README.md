# Proyecto Final: Languide - Plataforma de Aprendizaje de Idiomas

Lenguajes y herramientas principales: HTML5, CSS3, JavaScript (Vanilla), PHP 8.1, MySQL, y Ollama para generación de contenido (IA).

## 🔎 Resumen

Languide es una aplicación web responsiva para el aprendizaje de idiomas. Los usuarios se pueden registrar, iniciar sesión, configurar un plan de estudio (generado por IA) y practicar ejercicios que se generan dinámicamente. El sistema guarda el progreso por tema, marca temas como completados y muestra una barra de progreso visual.

## 📁 Estructura del repositorio
- `/html`
  - `landing-page.html`
  - `login-page.html`
  - `signup-page.html`
  - `forgot-password.html`
  - `recovery-sent.html`
  - `initial-setup.html`
  - `progress-page.html`
  - `question-page.html`
  - `correct-answer.html`
  - `wrong-answer.html`
  - `lesson-completed.html`
- `/css`
  - `utils.css`
  - `landing.css`
- `/php`
  - `db.php`
  - `generate_plan.php`
  - `get_questions.php`
  - `login.php`
  - `markprogress.php`
  - `progress.php`
  - `register.php`
- `/js`
  - `navbar.js`
- `/img`
- `README.md`
- `usuarios.sql`

## ✅ Requisitos (local)

* PHP 8.1 (o 8.x compatible)
* MySQL/MariaDB
* Servidor web (Apache/Nginx) o entorno con Vagrant que sirva la carpeta html en http://localhost:8080/html/...
* Navegador moderno

## 🚀 Instalación y ejecución

A continuación tienes dos opciones: ejecutar con Vagrant (si usas el Vagrantfile) o configurar manualmente un entorno LAMP/LEMP.

### Opción A — Con Vagrant (recomendado si existe Vagrantfile)

Clona el repositorio:
```
git clone <TU-REPO-URL>
cd <TU-REPO-FOLDER>
```

Levanta la VM:
```
vagrant up
```

Entra a la VM (opcional):
```
vagrant ssh
```

Abre en tu navegador:
```
http://localhost:8080/html/landing-page.html
```

Si tu Vagrantfile mapea otro puerto/ruta, usa la URL correspondiente. (Ejemplo usado en desarrollo): 
```
http://localhost:8080/html/landing-page.html)
```

### Opción B — Entorno local (LAMP)

Instala PHP 8.1, Apache (o Nginx) y MySQL/MariaDB.

Copia el contenido de la carpeta html a la raíz pública del servidor (por ejemplo www o htdocs).

Asegúrate que php está configurado y que el servidor puede ejecutar los scripts en la carpeta php.

Abre en tu navegador:
```
http://localhost/html/landing-page.html
```

## 🗄️ Base de datos — configuración inicial

```sql
-- Usuarios
CREATE TABLE `usuarios` (
  `id` int NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `temario_json` json DEFAULT NULL,
  `progreso_json` json DEFAULT NULL,
  `creado` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `usuario` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
```

### Importar el archivo usuarios.sql

Desde consola (MySQL):

```
mysql -u root -p < usuarios.sql
```

### 🔧 Configuración del backend (PHP)

Copia un archivo de configuración (por ejemplo php/config.example.php) a php/db.php y adapta los datos:

```php
<?php
// php/db.php
return [
  'host' => 'localhost',
  'user' => 'root',
  'pass' => 'tu_password',
  'dbname' => 'usuarios',
];
```

### Rutas principales:

* php/register.php — crea usuario (usa password_hash()).

* php/login.php — login, crea sesión PHP.

* php/generate_plan.php — construye prompt y solicita plan a la IA, guarda JSON en DB.

* php/get_questions.php — recupera ejercicios (desde IA)

* php/markprogress.php — marca día como completado.

* php/progress.php — devuelve plan y progreso para el usuario autenticado.

## 🧭 Flujo de uso (usuario)

Registro → Login.

Al primer login, el usuario configura preferencias (idioma, nivel, objetivo).

Backend genera un plan de 7 días y lo guarda.

Desde la vista de progreso, el usuario puede abrir cada tema y practicar.

Al practicar, el backend genera 3 ejercicios. Usuario responde; si completa correctamente, el backend marca el tema como completado y actualiza progreso.

Interfaz refleja progreso con barra y estados (Pendiente / Completado).
