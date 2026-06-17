# Cafe Management System

A web-based cafe management application built with PHP, MySQL, HTML, CSS, and JavaScript.

This system helps manage cafe operations through role-based dashboards for Admin, Staff, and Customer users. It includes menu management, customer ordering, cart handling, coupon management, and order tracking.

---

## Features

### Admin
- Manage users
- Manage staff accounts
- Add, edit, and delete menu products
- Manage coupons and special offers
- View and manage customer orders

### Staff
- View assigned orders
- Update order status
- Manage active payments and deliveries

### Customer
- Register and login
- Browse cafe menu
- Add products to cart
- Checkout orders
- Apply coupons
- View order history

---

## Tech Stack

- PHP
- MySQL / MariaDB
- HTML5
- CSS3
- JavaScript
- XAMPP / Apache Server

---

## Project Structure

```bash
cafe-management/
│
├── css/                     # Stylesheets
├── db/                      # Database configuration and connection files
├── js/                      # JavaScript files
├── php/                     # PHP logic and pages
│   ├── admin/               # Admin dashboard and features
│   ├── customer/            # Customer pages and ordering flow
│   └── staff/               # Staff dashboard and order handling
├── resources/               # Images and uploaded files
├── index.php                # Main entry point
└── README.md
```

---

## Installation and Setup

### 1. Clone the Repository

```bash
git clone https://github.com/iamskyline49/cafe-management.git
```

### 2. Move Project to XAMPP

Move the project folder to:

```bash
C:\xampp\htdocs\
```

Final path should look like:

```bash
C:\xampp\htdocs\cafe-management
```

### 3. Start XAMPP

Start:

```txt
Apache
MySQL
```

### 4. Create Database

Open phpMyAdmin:

```txt
http://localhost/phpmyadmin
```

Create a database:

```sql
CREATE DATABASE cafe;
```

### 5. Import Database

Import the SQL file from the `db/` folder if available.

### 6. Configure Database

Update database credentials inside:

```bash
db/config.php
```

Example:

```php
$host = "localhost";
$user = "root";
$password = "";
$database = "cafe";
```

### 7. Run the Project

Open in browser:

```txt
http://localhost/cafe-management/
```

---

## Demo Accounts

Update this section based on your actual database users:

```txt
Admin:
Email: admin@cafe.com
Password: 123456

Staff:
Email: staff@cafe.com
Password: 123456

Customer:
Email: customer@cafe.com
Password: 123456
```


---

## Future Improvements

- Add online payment gateway
- Add invoice generation
- Add email confirmation
- Improve UI responsiveness
- Add product search and filtering
- Add sales analytics dashboard

---

## Author

**Prottoy Sarker Diganto**  
GitHub: [iamskyline49](https://github.com/iamskyline49)

---

## License

This project was developed for academic and portfolio purposes.
