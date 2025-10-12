# Cafe Management System

A lightweight, self-hosted cafe management web application built with plain PHP, MySQL . This app provides role-based dashboards for Admin, Staff, and Customer and covers menu/product management, ordering, coupons, and basic user management.

---

## Table of Contents

- [About](#about)
- [Features](#features)
- [Tech Stack](#tech-stack)
- [Project Structure](#project-structure)
- [Prerequisites](#prerequisites)
- [Quick Start (Windows / XAMPP)](#quick-start-windows--xampp)
- [Database: Schema & Seed](#database-schema--seed)
- [Configuration](#configuration)
- [Usage (pages & flows)](#usage-pages--flows)
- [Troubleshooting](#troubleshooting)
- [Contributing](#contributing)
- [Contact](#contact)
- [Acknowledgements](#acknowledgements)

---

## About

This project is a simple web application intended for small cafes. It supports:

- Role-based access: Admin, Staff, Customer
- Menu/product management with image uploads
- Customer cart and checkout flow
- Order management and status updates
- Coupon and special-offer management

The codebase uses plain PHP for server logic, MySQL for data storage, and static HTML/CSS/JS for the UI.

## Features

- User registration, login, profile (customers, staff, admins)
- Admin area: manage users, employees, products, coupons, orders
- Staff area: view and update active orders and payments
- Customer area: browse menu, add to cart, checkout, view order history

## Tech Stack

- PHP (plain files, 7.x / 8.x compatible)
- MySQL / MariaDB
- HTML5, CSS3, JavaScript
- Developed for XAMPP style deployments

## Project Structure

Top-level files and folders (important parts):

- `index.php` — landing page / entry
- `css/` — styles (separate files for admin/customer/staff)
- `js/` — frontend scripts
- `db/` — `config.php`, `db_connection.php` (DB helpers)
- `php/` — authentication, register, login, reset-password, middleware
- `php/admin/` — admin controllers and pages
- `php/customer/` — customer pages (cart, checkout, menu)
- `php/staff/` — staff pages
- `resources/uploads/products/` — product images
- `resources/uploads/users/` — user avatars

## Prerequisites

- PHP (7.4+ recommended)
- MySQL or MariaDB
- Web server (Apache via XAMPP recommended on Windows)
- Git (optional)

## Quick Start (Windows / XAMPP)

1. Clone the repo into XAMPP's htdocs (or your web root):

powershell
cd C:\xampp\htdocs
git clone https://github.com/iamskyline49/cafe-management.git

2. Start Apache and MySQL through the XAMPP Control Panel.

3. Create a database (example name: `cafe`) .

Import with MySQL command-line (adjust path & credentials if needed):

````powershell
# create the database
mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS cafe;"

Use phpMyAdmin: open `http://localhost/phpmyadmin`, create `cafe`, then import `sql.txt`.

4. Ensure upload directories exist and are writable by the web server:

```powershell
New-Item -Path .\resources\uploads\products -ItemType Directory -Force
New-Item -Path .\resources\uploads\users -ItemType Directory -Force

# Grant write permissions (Windows):
icacls .\resources\uploads /grant "IIS_IUSRS:(OI)(CI)F" /T || icacls .\resources\uploads /grant "Users:(OI)(CI)F" /T
````

5. Update database credentials: edit `db\config.php` and set your host, user, password, and database name.

6. Open in browser:

```
http://localhost/cafe-management/
```

## Database: Schema & Seed

Below is a suggested schema and example seed inserts. Import both to create tables and example data.

-- Schema

```sql
-- Users Table
CREATE TABLE users (
	id INT AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(100) NOT NULL,
	email VARCHAR(150) NOT NULL UNIQUE,
	password VARCHAR(255) NOT NULL,
	photo VARCHAR(255) NULL,
	role VARCHAR(50) NOT NULL DEFAULT 'customer'
);

-- Staff Table (links to a user)
CREATE TABLE staff (
	id INT AUTO_INCREMENT PRIMARY KEY,
	userId INT NOT NULL,
	dutyFrom TIME NULL,
	dutyTo TIME NULL,
	FOREIGN KEY (userId) REFERENCES users(id) ON DELETE CASCADE
);

-- Products Table
CREATE TABLE products (
	id INT AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(150) NOT NULL,
	price DECIMAL(10,2) NOT NULL,
	image VARCHAR(255) NULL
);

-- Coupons Table
CREATE TABLE coupons (
	id INT AUTO_INCREMENT PRIMARY KEY,
	couponString VARCHAR(50) NOT NULL,
	percentage DECIMAL(5,2) NOT NULL,
	active BOOLEAN NOT NULL DEFAULT TRUE
);

-- Special Offers Table
CREATE TABLE special_offers (
	id INT AUTO_INCREMENT PRIMARY KEY,
	title VARCHAR(255) NOT NULL,
	description TEXT NOT NULL,
	image VARCHAR(255) NOT NULL,
	genuine_price DECIMAL(10,2) NOT NULL,
	discount DECIMAL(5,2) NOT NULL,
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Orders Table
CREATE TABLE orders (
	id INT AUTO_INCREMENT PRIMARY KEY,
	staffId INT NULL,
	userId INT NULL,
	productId INT NULL,
	quantity INT NOT NULL DEFAULT 1,
	couponId INT NULL,
	payment_method VARCHAR(50) NULL,
	status ENUM('pending', 'delivered') NOT NULL DEFAULT 'pending',
	specialOfferId INT NULL,
	is_special_offer BOOLEAN NOT NULL DEFAULT FALSE,
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	FOREIGN KEY (staffId) REFERENCES staff(id) ON DELETE SET NULL,
	FOREIGN KEY (userId) REFERENCES users(id) ON DELETE SET NULL,
	FOREIGN KEY (productId) REFERENCES products(id) ON DELETE SET NULL,
	FOREIGN KEY (couponId) REFERENCES coupons(id) ON DELETE SET NULL,
	FOREIGN KEY (specialOfferId) REFERENCES special_offers(id) ON DELETE SET NULL
);

-- Cart Table
CREATE TABLE cart (
	id INT AUTO_INCREMENT PRIMARY KEY,
	userId INT NOT NULL,
	productId INT NOT NULL,
	quantity INT NOT NULL DEFAULT 1,
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	FOREIGN KEY (userId) REFERENCES users(id) ON DELETE CASCADE,
	FOREIGN KEY (productId) REFERENCES products(id) ON DELETE CASCADE
);
```

-- Example seed data

````sql
-- Users
INSERT INTO users (name, email, password, photo, role) VALUES
('Admin User', 'admin@coffeeshop.com', '123', NULL, 'admin'),
('Staff User', 'barista@coffeeshop.com', '123', NULL, 'staff'),
('Alice Johnson', 'alice@customer.com', '123', NULL, 'customer');

-- Staff
INSERT INTO staff (userId, dutyFrom, dutyTo) VALUES
(2, '08:00:00', '16:00:00');

-- Products
INSERT INTO products (name, price) VALUES
('Espresso', 2.50),
('Cappuccino', 3.50),
('Latte', 4.00);

-- Coupons
INSERT INTO coupons (couponString, active) VALUES
('WELCOME5', TRUE),
('FREEMUFFIN', TRUE);


## Configuration

Edit `db/config.php` to set your database credentials. An example `db/config.php` could look like:

```php
<?php
$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = '';
$DB_NAME = 'cafe';
?>
````

The application uses this configuration to create a mysqli/PDO connection in `db/db_connection.php`.

## Usage (pages & flows)

- Public: `index.php`, menu pages
- Auth: `register.php`, `login.php`, `logout.php`, `reset-password.php`
- Customer: `php/customer/menu.php`, `php/customer/cart.php`, `php/customer/checkout.php`, `php/customer/order-history.php`
- Admin: `php/admin/dashboard.php`, `php/admin/manage-products.php`, `php/admin/manage-users.php`, `php/admin/manage-coupons.php`
- Staff: `php/staff/staff-orders.php`, `php/staff/staff-profile.php`

Check `php/auth_middleware.php` for role checks and how access is enforced.

## Troubleshooting

- Blank pages -> enable PHP error display in `php.ini` or check Apache/PHP error logs.
- Database connection errors -> verify `db/config.php` and that MySQL is running.
- File uploads failing -> check directory permissions on `resources/uploads/`.

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make changes and test locally
4. Open a pull request with a clear description

## Contact

Repository: `https://github.com/iamskyline49/cafe-management.git`
