# Affiliate and Course Management Platform

A comprehensive affiliate and direct sales management platform built with Laravel 10, PHP 8, MySQL, and Bootstrap. The platform focuses on information products and digital courses with automation, security, and an intuitive user experience.

## Features

### Module 1: Administrator Panel
- ✅ **Secure Login**: Two-Factor Authentication (2FA) and Google reCAPTCHA
- ✅ **Dashboard**: Comprehensive analytics with charts and statistics
- 🔄 **System Settings**: Customization, SEO, and tracking tags
- 🔄 **Plan Management**: Product registration with commission rules
- 🔄 **Sales Management**: Direct sales and invoice management
- 🔄 **Financial Module**: Withdrawals, expenses, and reports
- 🔄 **Course Module (EAD)**: Unlimited courses with modules and lessons
- 🔄 **Automation**: Cron jobs for profit sharing and bonuses

## Technology Stack

- **Backend**: Laravel 10, PHP 8.1+
- **Database**: MySQL
- **Frontend**: Bootstrap 5, Chart.js
- **Authentication**: Laravel Sanctum, 2FA
- **Security**: reCAPTCHA, CSRF protection
- **Charts**: Chart.js (as per user preference)

## Installation

### Prerequisites
- PHP 8.1 or higher
- Composer
- MySQL 5.7 or higher
- Node.js and NPM

### Setup Steps

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd affiliate-platform
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment setup**
   ```bash
   cp env.example .env
   php artisan key:generate
   ```

4. **Database configuration**
   - Update `.env` file with your database credentials
   - Create the database: `affiliate_platform`

5. **Run migrations and seeders**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

6. **Storage setup**
   ```bash
   php artisan storage:link
   ```

7. **Start the application**
   ```bash
   php artisan serve
   ```

## Default Admin Credentials

- **Email**: admin@example.com
- **Password**: password123

## Environment Variables

Key environment variables to configure:

```env
# Application
APP_NAME="Affiliate Course Platform"
APP_URL=http://localhost

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=affiliate_platform
DB_USERNAME=root
DB_PASSWORD=

# reCAPTCHA
RECAPTCHA_SITE_KEY=your_site_key
RECAPTCHA_SECRET_KEY=your_secret_key

# Google Analytics
GOOGLE_ANALYTICS_ID=
GOOGLE_TAG_MANAGER_ID=

# Meta Pixel
META_PIXEL_ID=

# Financial Settings
WITHDRAWAL_DAYS="1,2,3,4,5"
WITHDRAWAL_START_TIME="09:00"
WITHDRAWAL_END_TIME="18:00"
WITHDRAWAL_FEE_TYPE=percentage
WITHDRAWAL_FEE_VALUE=5.00
```

## Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   └── Admin/          # Admin panel controllers
│   ├── Middleware/         # Custom middleware
│   └── Requests/           # Form request validation
├── Models/                 # Eloquent models
└── Providers/              # Service providers

database/
├── migrations/             # Database migrations
└── seeders/               # Database seeders

resources/
├── views/
│   ├── admin/             # Admin panel views
│   └── layouts/           # Layout templates
└── js/                    # JavaScript assets

routes/
└── web.php                # Web routes
```

## Key Models

- **Admin**: Administrator users with roles and permissions
- **User**: Regular users and affiliates
- **Plan**: Sales plans with products and courses
- **Course**: EAD courses with modules and lessons
- **Sale**: Sales transactions and commissions
- **SystemSetting**: Configurable system settings

## Security Features

- Two-Factor Authentication (2FA) for admin users
- Google reCAPTCHA integration
- CSRF protection
- Role-based access control
- Secure password hashing
- Input validation and sanitization

## Development Status

- ✅ **Completed**: Project setup, authentication system, basic models
- 🔄 **In Progress**: System settings, plan management
- ⏳ **Pending**: Sales management, financial module, course system, automation

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests if applicable
5. Submit a pull request

## License

This project is licensed under the MIT License.

## Support

For support and questions, please contact the development team.

