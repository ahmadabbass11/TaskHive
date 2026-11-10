# Task Hive

Task Hive is a full-stack team collaboration platform developed as a Computer Science capstone project. It brings project management, task tracking, team communication, and file sharing into one application.

The project combines a Laravel backend with React/Inertia pages and Blade views. It also integrates real-time messaging through Redis and Laravel Echo Server, video calls through Twilio, and feedback moderation through a Hugging Face model.

## Features

- **Team management:** create teams, manage members, and update member roles.
- **Projects and tasks:** create projects, assign tasks, and organize associated milestones.
- **Messaging:** create conversations, send messages, and broadcast updates to connected clients.
- **File sharing:** upload task attachments and download shared files.
- **Video calls:** generate Twilio access tokens for browser-based calls.
- **User accounts:** registration, login, password reset, and profile management.
- **Permissions:** role checks and authenticated routes for application features.
- **Feedback:** submit feedback with an external toxicity-classification integration.

## Technology stack

| Area | Technologies |
| --- | --- |
| Backend | PHP 8.2+, Laravel 11, Eloquent ORM |
| Frontend | React 18, Inertia.js 2, Blade, Tailwind CSS, Vite |
| Database | Laravel database migrations and relational models |
| Real-time communication | Redis, Laravel Echo, Laravel Echo Server, Socket.IO |
| Video | Twilio Video |
| Moderation | Hugging Face inference API |
| Testing | Pest, PHPUnit |

## Application structure

```text
app/                 Controllers, models, events, and providers
config/              Application and service configuration
database/            Migrations, factories, and seeders
resources/js/        React components, layouts, and pages
resources/views/     Blade application views
routes/              Web and authentication routes
tests/               Feature and unit tests
laravel-echo-server.json
```

## Local setup

### Prerequisites

- PHP 8.2 or later and the extensions required by Laravel and your database driver.
- Composer.
- Node.js and npm compatible with the included package lockfile.
- A database supported by the project configuration; configure your chosen connection in `.env`.
- Redis for real-time messaging.
- Twilio credentials for video calls and a Hugging Face token for the moderation integration.

### 1. Install dependencies

Open a terminal at the repository root, where `artisan` and `composer.json` are located:

```bash
composer install
npm ci
cp .env.example .env
php artisan key:generate
```

### 2. Configure the environment

Edit `.env` with your local database, Redis, and application settings. For a local MySQL database, for example:

```dotenv
APP_NAME="Task Hive"
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=taskhive
DB_USERNAME=your_local_database_user
DB_PASSWORD=your_local_database_password

BROADCAST_DRIVER=redis
QUEUE_CONNECTION=database
REDIS_CLIENT=predis
REDIS_HOST=127.0.0.1
REDIS_PORT=6379

TWILIO_ACCOUNT_SID=
TWILIO_API_KEY=
TWILIO_API_SECRET=
HUGGINGFACE_API_KEY=
```

Create the database before running migrations. The broadcasting configuration reads `BROADCAST_DRIVER`; the starter `.env.example` may instead contain `BROADCAST_CONNECTION`, so ensure the Redis driver is explicitly configured.

Keep real credentials in your local `.env` only. External services may require a paid account or service-specific configuration.

### 3. Initialize the application

```bash
php artisan migrate
php artisan storage:link
php artisan config:clear
```

Register a user through the application. Review the seeders before using them; they are intended for development rather than production account provisioning.

### 4. Start the web application

Run these commands in separate terminals:

```bash
php artisan serve
```

```bash
npm run dev
```

Open `http://localhost:8000`.

### 5. Enable real-time messaging

Start your Redis service and check `laravel-echo-server.json`. Its application URL, Redis settings, CORS configuration, and authentication endpoint must match your local environment. The frontend currently connects to the page hostname on port `6001`.

Then run these commands in separate terminals:

```bash
npx laravel-echo-server start
```

```bash
php artisan queue:work
```

### 6. Configure optional integrations

- **Video calls:** provide Twilio account, API key, and API secret values. Test browser camera and microphone permissions.
- **Feedback moderation:** configure `HUGGINGFACE_API_KEY` and check that the model endpoint in `FeedbackController` is available for your account. Provider endpoints and access requirements may need updating.

## Tests and frontend build

```bash
php artisan test
npm run build
```

The repository includes authentication and profile feature tests alongside starter tests. These commands are provided for local verification; this README does not claim complete feature coverage or a verified passing test run.

## Project status

This repository presents an academic project, not a production deployment. Real-time communication and third-party integrations require environment-specific setup. Before production use, review channel authorization, upload controls, access checks, HTTPS/TLS settings, error handling, and dependencies. The moderation request currently disables TLS certificate verification and allows feedback through when the external service fails; those behaviours should be revised before deployment.

## Author

**Ahmad Abbas** — Computer Science capstone project, Lebanese American University.
