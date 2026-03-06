# PSK Management Platform - Deployment Guide

## Laravel Cloud Deployment

### Build Commands
```
composer install --no-dev --optimize-autoloader
npm ci
npm run build
php artisan optimize
```

### Deploy Commands
```
php artisan migrate --force
php artisan db:seed --class=RoleAndPermissionSeeder --force
php artisan db:seed --class=MembershipTierSeeder --force
php artisan db:seed --class=CpdCategorySeeder --force
php artisan db:seed --class=TicketCategorySeeder --force
php artisan db:seed --class=SettingsSeeder --force
php artisan db:seed --class=NumberSequenceSeeder --force
```

### Environment Variables

Laravel Cloud auto-injects database credentials. Set these manually:

| Variable | Description | Example |
|----------|-------------|---------|
| `APP_NAME` | Application name | `PSK Management` |
| `APP_ENV` | Environment | `production` |
| `APP_DEBUG` | Debug mode | `false` |
| `APP_URL` | Application URL | `https://psk.laravel.cloud` |
| `MAIL_MAILER` | Mail driver | `smtp` |
| `MAIL_HOST` | SMTP host | `smtp.mailgun.org` |
| `MAIL_PORT` | SMTP port | `587` |
| `MAIL_USERNAME` | SMTP username | |
| `MAIL_PASSWORD` | SMTP password | |
| `MAIL_FROM_ADDRESS` | Sender email | `noreply@psk.or.ke` |

### Services to Enable

1. **Database**: MySQL (provisioned via Cloud dashboard)
2. **Queue Worker**: Enable in Cloud dashboard (processes payments, notifications, emails)
3. **Scheduler**: Enable in Cloud dashboard (runs membership reminders, expiry checks)

### Post-Deployment Checklist

1. Create the initial Super Admin user via tinker or a seeder
2. Configure mail credentials for notifications
3. Verify queue worker is processing jobs
4. Verify scheduler is running (`php artisan schedule:list`)

### Local Development

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed
npm run dev
php artisan serve
```
