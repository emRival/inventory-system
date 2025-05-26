<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Getting Started from Scratch (Windows)

To set up this Laravel project on a Windows system, follow these steps:

### Prerequisites

1. **Option 1: Install Individually**:
    - **Install PHP**: Ensure PHP version 8.3 or higher is installed. You can download it from [php.net](https://www.php.net/downloads). Add PHP to your system's PATH environment variable.
    - **Install Composer**: Download and install Composer from [getcomposer.org](https://getcomposer.org/download/). During installation, ensure PHP is correctly detected.
    - **Install Node.js and npm**: Download and install Node.js from [nodejs.org](https://nodejs.org/). The npm package manager is included with Node.js.
    - **Install MySQL**: Download and install MySQL from [mysql.com](https://dev.mysql.com/downloads/). During installation, configure the root password and ensure the MySQL service is running.

2. **Option 2: Use Herd**:
    - If you prefer a simpler setup, you can download [Herd](https://herd.laravel.com/), which bundles PHP, Composer, and other tools required for Laravel development.

### Project Setup

1. **Clone the Repository**:
    ```bash
    git clone https://github.com/your-repo/inventory-system.git
    cd inventory-system
    ```

2. **Copy `.env` File**:
    ```bash
    copy .env.example .env
    ```

3. **Update `.env` Configuration**:
    - Set the database connection to MySQL:
        ```
        DB_CONNECTION=mysql
        DB_HOST=127.0.0.1
        DB_PORT=3306
        DB_DATABASE=your_database_name
        DB_USERNAME=your_username
        DB_PASSWORD=your_password
        ```

4. **Install Dependencies**:
    - Install PHP dependencies:
        ```bash
        composer install
        ```
    - Install JavaScript dependencies:
        ```bash
        npm install
        ```

5. **Generate Application Key**:
    ```bash
    php artisan key:generate
    ```

6. **Run Migrations and Seeders**:
    ```bash
    php artisan migrate
    php artisan db:seed
    ```

7. **Build Frontend Assets**:
    ```bash
    npm run dev
    ```

8. **Start the Development Server**:
    ```bash
    php artisan serve
    ```

9. **Queue Worker**:
    Start the queue worker for background tasks:
    ```bash
    php artisan queue:work --daemon
    ```

10. **Generate Shield Permissions**:
    ```bash
    php artisan shield:generate --all
    ```

11. **Create Filament User**:
    ```bash
    php artisan make:filament-user
    ```

12. **Create Super Admin**:
    ```bash
    php artisan shield:super-admin
    ```



### Additional Notes

- Ensure your `.env` file is properly configured for your environment.
- For production, use `npm run build` to compile assets and configure a web server like IIS, Nginx, or Apache.

You are now ready to start using the Laravel project!
