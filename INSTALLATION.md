
- A **user** can create many tickets.
- A **ticket** can be assigned to a **user**.
- Admin has global access.

---

## ⚙️ Installation Manual

### 🧰 Requirements
- PHP >= 8.2
- Composer
- Node.js & npm
- MySQL or MariaDB
- Laravel CLI

### 🪜 Steps

```bash
# 1. Clone the repository
git clone https://github.com/<your-username>/<your-repo>.git
cd your-repo

# 2. Install dependencies
composer install
npm install

# 3. Copy environment file
cp .env.example .env

# 4. Configure database
# Update DB_DATABASE, DB_USERNAME, DB_PASSWORD in .env

# 5. Generate app key
php artisan key:generate

# 6. Run migrations
php artisan migrate

# 7. Build front-end assets
npm run dev

# 8. Start server
php artisan serve
