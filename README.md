# Doctor Management Platform

A Laravel-based healthcare and appointment management system for managing doctors, patients, schedules, appointments, transactions, content, and admin workflows.

## Overview

This project provides a complete web application for a medical practice or clinic, including:

- Doctor and patient management
- Appointment scheduling and tracking
- Transaction and payment handling
- Admin dashboard and role-based access control
- Content management for health education articles
- Assessment and review modules
- Email and queue processing for notifications

## Tech Stack

- PHP 8.1+
- Laravel 8
- MySQL / MariaDB
- Bootstrap + Laravel Mix
- Composer
- npm
- Stripe, Twilio, Dompdf, and related integrations

## Features

- Admin dashboard with summary statistics
- Manage doctors, patients, and administrators
- Create and update clinic schedules
- Book, edit, and cancel appointments
- Export appointment and transaction records
- Handle patient transactions and receipts
- Review medical content and publish updates
- Collect and review patient assessment answers
- Send email notifications through queued jobs
- Search and audit login activity across users

## Project Structure

- `app/` – application logic, controllers, models, helpers, jobs, and services
- `config/` – Laravel configuration files
- `database/` – migrations, factories, and seeders
- `public/` – public assets and web entry point
- `resources/` – frontend templates, Sass, JS, and views
- `routes/` – HTTP route definitions
- `tests/` – PHPUnit test suite

## Prerequisites

Before running the project, make sure you have:

- PHP 8.1 or newer
- Composer
- Node.js and npm
- A database server such as MySQL
- A local web server or Laravel artisan server

## Installation

1. Clone the repository:

   ```bash
   git clone <repository-url>
   ```

2. Install PHP dependencies:

   ```bash
   composer install
   ```

3. Create your environment file:

   ```bash
   cp .env.example .env
   ```

   If the project does not include a `.env.example` file in your environment, create a `.env` file manually and add your database and app settings.

4. Generate the application key:

   ```bash
   php artisan key:generate
   ```

5. Configure your database in `.env`:

   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=doctor
   DB_USERNAME=root
   DB_PASSWORD=
   ```

6. Run database migrations and seeders:

   ```bash
   php artisan migrate
   php artisan db:seed
   ```

   If the app has a custom seeder or requires a specific class, run the matching command from the project docs or the seeder files under `database/seeds`.

7. Start the application:

   ```bash
   php artisan serve
   ```

   Then visit `http://127.0.0.1:8000` in your browser.

## Common Commands

```bash
composer dump-autoload
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan migrate:fresh --seed
php artisan queue:work
```

## Queue and Email Notes

This project includes queue-based jobs for notifications and background processing. If email delivery or background tasks are required, run:

```bash
php artisan queue:work
```

## License

This project is licensed under the MIT License.

## Notes

The repository appears to be a legacy Laravel application and may require compatibility adjustments depending on the local PHP and dependency versions in your environment.
