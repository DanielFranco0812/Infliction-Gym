# Infliction Gym

Infliction Gym is a Laravel website for the gym in Magalang, Pampanga. It includes public membership information, account registration, a member dashboard, coach schedules, support chat, and PayMongo-hosted checkout.

## Requirements

- PHP 8.2 or newer with `pdo_sqlite`, `mbstring`, `openssl`, `fileinfo`, `tokenizer`, `xml`, `ctype`, and `json` enabled
- Composer 2
- Node.js 20 or newer and npm
- Git

The default local setup uses SQLite, so a separate database server is not required. XAMPP can provide PHP on Windows; Composer, Node.js/npm, and Git must also be installed and available in PowerShell or your terminal.

## Download and install

Clone the repository and enter the project folder:

```sh
git clone https://github.com/DanielFranco0812/Infliction-Gym.git
cd Infliction-Gym
```

Install PHP dependencies and create your local environment file. On Windows PowerShell:

```powershell
composer install
Copy-Item .env.example .env
New-Item -ItemType File -Path database/database.sqlite -Force
php artisan key:generate
php artisan migrate
npm install
```

On macOS/Linux, use these equivalents for the environment file and SQLite database:

```sh
cp .env.example .env
touch database/database.sqlite
```

Run those two commands before `php artisan key:generate` and `php artisan migrate`.

## Run locally

Open two terminals in the project folder.

Terminal 1:

```sh
php artisan serve
```

Terminal 2:

```sh
npm run dev
```

Open <http://127.0.0.1:8000>. Keep both commands running while you use the site. To build frontend assets without the Vite development server, run `npm run build`.

## Optional integrations

The site runs without these credentials, but their related features will be limited.

### Support chat

The chat uses the Groq API for generated responses. Add your key to `.env`:

```dotenv
GROQ_API_KEY=your_groq_api_key
```

Never commit `.env` or publish API keys.

### PayMongo checkout

To test checkout, create PayMongo test-mode API and webhook credentials and set them in `.env`:

```dotenv
PAYMONGO_SECRET_KEY=sk_test_your_key
PAYMONGO_WEBHOOK_SECRET=your_test_webhook_secret
PAYMONGO_PAYMENT_METHOD_TYPES=qrph
```

Register an HTTPS webhook endpoint at `https://your-domain/webhooks/paymongo` in PayMongo and subscribe it to `checkout_session.payment.paid`. Localhost is not publicly reachable by PayMongo; use a secure public development tunnel for webhook testing. Checkout is currently a one-time payment, not an automatic monthly renewal.

## Tests

Run the automated test suite:

```sh
php artisan test
```

## Deployment notes

For deployment, configure a production database, HTTPS, `APP_ENV=production`, and `APP_DEBUG=false`. Set real service credentials in the hosting environment, not in source control. User registration requires agreement to the Terms of Service.
