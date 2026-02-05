# 📦 Order Tracking System

A comprehensive order tracking system built with Laravel 10, featuring a modern admin panel powered by Filament and real-time public tracking interface using Livewire.

## 📝 Overview

This system provides a complete solution for managing and tracking service orders (particularly editing services). Clients can track their order progress in real-time using a unique tracking code, while administrators manage orders through an intuitive Filament admin panel.

## ✨ Key Features

- **🎯 Order Management**: Complete CRUD operations for managing orders
- **📊 Progress Tracking**: Real-time progress updates with timeline view
- **📎 File Attachments**: Upload and manage files related to orders
- **🔍 Public Tracking**: Client-facing tracking page with unique order codes
- **👨‍💼 Admin Panel**: Modern and intuitive admin interface built with Filament
- **⚡ Real-time Updates**: Livewire-powered dynamic components
- **📱 Responsive Design**: Mobile-friendly interface using Tailwind CSS
- **🔐 Secure Authentication**: Built-in authentication for admin users
- **📧 Email Integration**: Order notifications and updates

## 🛠️ Tech Stack

- **Framework**: [Laravel 10](https://laravel.com)
- **Admin Panel**: [Filament 3.x](https://filamentphp.com)
- **Frontend**: [Livewire 3.x](https://laravel-livewire.com) + [Alpine.js](https://alpinejs.dev)
- **Styling**: [Tailwind CSS](https://tailwindcss.com)
- **Database**: MySQL / PostgreSQL
- **PHP**: ^8.1
- **Authentication**: Filament Auth

## 📋 Prerequisites

Before you begin, ensure you have the following installed:

- PHP >= 8.1
- Composer
- Node.js & NPM
- MySQL / PostgreSQL
- Web Server (Apache / Nginx / Laravel Valet / Laragon)

## 🚀 Installation

### 1. Clone the Repository

```bash
git clone <repository-url>
cd order-tracking-system
```

### 2. Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install Node dependencies
npm install
```

### 3. Environment Configuration

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Database Setup

Configure your database in the `.env` file:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=order_tracking
DB_USERNAME=root
DB_PASSWORD=
```

Then run migrations:

```bash
# Run migrations
php artisan migrate

# (Optional) Seed database with sample data
php artisan db:seed
```

### 5. Create Admin User

```bash
# Create your first admin user
php artisan make:filament-user
```

Follow the prompts to set up your admin credentials.

### 6. Build Assets

```bash
# Build frontend assets
npm run build

# Or for development with hot reload
npm run dev
```

### 7. Start Development Server

```bash
php artisan serve
```

Visit `http://localhost:8000` for the public site and `http://localhost:8000/admin` for the admin panel.

## 🌐 Quick Start with Ngrok (Optional)

For sharing your local development environment or webhook testing, see the complete guide in [NGROK_SETUP.md](NGROK_SETUP.md).

**Quick Steps:**
1. Copy `.env.ngrok.example` to `.env`
2. Run `php artisan key:generate`
3. Setup database and run migrations
4. Execute `start-ngrok.bat`
5. Update `APP_URL` in `.env` with ngrok URL
6. Run `php artisan config:clear`

## 📂 Project Structure

```
order-tracking-system/
├── app/
│   ├── Filament/          # Filament admin resources & widgets
│   ├── Http/
│   │   ├── Controllers/   # Application controllers
│   │   └── Requests/      # Form request validations
│   ├── Livewire/          # Livewire components for tracking
│   └── Models/            # Eloquent models
├── database/
│   ├── migrations/        # Database migrations
│   └── seeders/           # Database seeders
├── resources/
│   ├── css/               # Stylesheets
│   ├── js/                # JavaScript files
│   └── views/             # Blade templates
└── routes/
    └── web.php            # Web routes
```

## 🎯 Usage

### Admin Panel

1. Access the admin panel at `/admin`
2. Login with your admin credentials
3. Manage orders, progress updates, and files
4. View dashboard with order statistics

### Public Tracking

1. Clients visit the tracking page at `/track`
2. Enter their unique order code
3. View real-time order status and progress
4. Download attached files (if any)

## 💾 Database Schema

### Orders Table
- `order_code`: Unique tracking identifier
- `client_name`: Client's name
- `client_email`: Client's email
- `service_type`: Type of service requested
- `status`: Order status (draft, in_progress, review, revision, completed, cancelled)
- `estimated_completion`: Expected completion date
- `actual_completion`: Actual completion date

### Order Progress Table
- Tracks individual progress updates
- Linked to orders via foreign key
- Includes title, description, and timestamp

### Order Files Table
- Manages file attachments
- Supports multiple files per order
- Stores file metadata and paths

## 🔧 Configuration

### Filament Resources

Generate new Filament resources:

```bash
php artisan make:filament-resource ModelName --generate --view
```

### Livewire Components

Create new Livewire components:

```bash
php artisan make:livewire ComponentName
```

### Customize Appearance

Edit Tailwind configuration in `tailwind.config.js` and rebuild:

```bash
npm run build
```

## 🧪 Testing

```bash
# Run tests
php artisan test

# Run specific test
php artisan test --filter TestName
```

## 📚 Additional Documentation

- [Implementation Guide](IMPLEMENTATION_GUIDE.md) - Detailed implementation steps
- [Development Tasks](DEVELOPMENT_TASKS.md) - Current development roadmap
- [Ngrok Setup](NGROK_SETUP.md) - Local development with Ngrok
- [Performance Checklist](PERFORMANCE_CHECKLIST.md) - Optimization guide

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

1. Fork the project
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 🔒 Security

If you discover any security-related issues, please email the development team instead of using the issue tracker.

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 🙏 Acknowledgments

- [Laravel](https://laravel.com) - The PHP Framework
- [Filament](https://filamentphp.com) - Admin Panel Framework
- [Livewire](https://laravel-livewire.com) - Full-stack Framework
- [Tailwind CSS](https://tailwindcss.com) - Utility-first CSS Framework

## 📞 Support

For support, please open an issue in the GitHub repository or contact the development team.

---

**Built with ❤️ using Laravel & Filament**
