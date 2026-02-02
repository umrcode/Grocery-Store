# QuickCart — Online Retail Store 🛒

**QuickCart** is a demo online retail application built with PHP, MySQL, HTML, CSS and a lightweight JavaScript frontend for client-side cart behavior. It demonstrates a simple e-commerce workflow with customer, admin and delivery agent roles, product management, cart & checkout flow, and basic wallet support.

---

## Table of Contents 📚
- [Features](#features)
- [Tech Stack](#tech-stack)
- [Project Structure](#project-structure)
- [Prerequisites](#prerequisites)
- [Installation & Setup](#installation--setup)
- [Running the App](#running-the-app)
- [Demo Accounts & Usage](#demo-accounts--usage)
- [Notes & Security Recommendations ⚠️](#notes--security-recommendations)
- [Contributing](#contributing)
- [Screenshots](#screenshots)
- [License](#license)

---

## Features ✅
- Product catalog with categories (server-side products from MySQL)
- Add-to-cart (DB-backed for logged-in users, localStorage for guests)
- Checkout that creates orders and decrements product stock
- Admin pages for managing products, categories and viewing orders (existing in `admin_mode/`)
- Delivery agent interface for tracking deliveries
- Simple wallet system for customers

## Tech Stack 🧩
- PHP (Vanilla)
- MySQL / MariaDB (database schema in `quickcart.sql`)
- HTML, CSS, Bootstrap (front-end)
- Plain JavaScript for client-side cart features

## Project Structure 📁
Important folders and files:
- `QuickCart/` — main application
  - `admin_mode/`, `customer_mode/`, `agent_mode/` — role-specific pages
  - `functions/common_function.php` — core DB helpers and cart functions
  - `includes/connect.php` — MySQL connection (update credentials here)
  - `quickcart.sql` — database schema and seed data
  - `images/` — product images used by server pages
- `QuickCart/html/` — new demo frontend (static & PHP pages + assets)
  - `assets/` — JS, CSS, sample product data

## Prerequisites ⚙️
- XAMPP (Apache + PHP + MySQL) installed and running
- PHP 7.4+ recommended
- MySQL / phpMyAdmin

## Installation & Setup 🔧
1. Clone the repository:
   ```bash
   git clone <repo-url>
   ```
2. Place the project folder under your webroot (e.g., `C:\xampp\htdocs` on Windows).
3. Import the database schema (`quickcart.sql`) via phpMyAdmin or using the MySQL CLI:
   - phpMyAdmin → Import → choose `quickcart.sql`
   - or: `mysql -u root -p < quickcart.sql`
4. Update DB credentials in `QuickCart/includes/connect.php` if needed (host, user, password, database).
5. Ensure Apache and MySQL are started in XAMPP.

## Running the App ▶️
Open in your browser (adjust path if you placed the project in a different folder):
- http://localhost/QuickCart/html/index.php  (public storefront)
- Admin login: http://localhost/QuickCart/admin_mode/admin_login.php
- Customer login: http://localhost/QuickCart/customer_mode/customer_login.php

If you prefer the PHP built-in server (for quick local testing without XAMPP):
```bash
php -S localhost:8000 -t "C:/xampp/htdocs/QuickCart-Online-Retail-Store-main/QuickCart"
# then open http://localhost:8000/html/index.php
```

## Demo Accounts & Quick Usage 🔐
- Example customer: **hassan@quickcart.com** / **Hassan@123$** (from `quickcart.sql` seeds)
- Admins are seeded in the `admin` table - use admin login pages to access admin features.

## Notes & Security Recommendations ⚠️
- Passwords in the seed data are stored in plaintext. **Do not** use this in production. Migrate to `password_hash()` / `password_verify()` and require users to reset passwords.
- Use a dedicated DB user with a strong password (avoid using `root` on production).
- Consider moving DB credentials to an environment file (`.env`) and **exclude** it from Git using `.gitignore`.
- Validate and sanitize all user input on server-side to prevent SQL injection (prepared statements recommended).

## Contributing 🙌
1. Fork the repo and create a feature branch.
2. Make changes and test locally.
3. Open a PR with a clear description of changes.

Please follow standard GitHub PR workflow and write meaningful commit messages.

## Screenshots 📸
See `App-Snapshots/` for example customer/admin/agent screens used in the project.

---

If you'd like, I can also:
- Add a `LICENSE` file (MIT suggested),
- Add CI checks (PHP linting), or
- Create a short CONTRIBUTING.md with contribution guidelines.

If you want me to push this README to the repo and create a `LICENSE` file, tell me which license you prefer (MIT recommended) and I'll add them.

