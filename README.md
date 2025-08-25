# Laravel To-Do Management System

A modern To-Do application built with Laravel, featuring AJAX CRUD, authentication, theme toggle, and database storage.

## Features

-   User authentication (Laravel Breeze)
-   Add, edit, delete, and toggle tasks (AJAX)
-   Pagination (AJAX)
-   Theme toggle (light/dark)
-   Laravel Collective Forms
-   Yajra Datatables (installed)
-   Tailwind CSS (with Vite)
-   CSRF protection
-   Full validation and error/success messaging

## Requirements

-   PHP >= 8.1
-   Composer
-   Node.js & npm
-   SQLite/MySQL/Postgres (default: SQLite)

## Setup Instructions

### 1. Clone the Repository

```
git clone https://github.com/amitsarker95/To-Do-Management-System-Laravel.git
cd To-Do-Management-System-Laravel
```

### 2. Install PHP Dependencies

```
composer install
```

### 3. Install Node Dependencies

```
npm install
```

### 4. Environment Setup

```
cp .env.example .env
php artisan key:generate
```

### 5. Database Setup

-   By default, uses SQLite. You can change to MySQL/Postgres in `.env`.
-   For SQLite:

```
touch database/database.sqlite
```

-   Update `.env`:

```
DB_CONNECTION=sqlite
DB_DATABASE=./database/database.sqlite
```

### 6. Run Migrations

```
php artisan migrate
```

### 7. (Optional) Seed Database

```
php artisan db:seed
```

### 8. Build Frontend Assets

```
npm run build
```

### 9. Start the Development Server

```
php artisan serve
```

Visit [http://localhost:8000](http://localhost:8000)

## Authentication

-   Register and login using the provided forms.
-   Each user manages their own tasks.

## Usage

-   Add, edit, delete, and toggle tasks from the dashboard.
-   Use the theme toggle button to switch between light and dark mode.
-   Tasks are paginated and updated dynamically via AJAX.

## Packages Used

-   [laravelcollective/html](https://laravelcollective.com/docs/6.x/html)
-   [yajra/laravel-datatables](https://yajrabox.com/docs/laravel-datatables)
-   [tailwindcss](https://tailwindcss.com/)
-   [laravel/breeze](https://laravel.com/docs/10.x/starter-kits#breeze)

## License

@AMIT SARKER
