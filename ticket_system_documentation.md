# 📃 Ticket Management System Documentation

## 1. Overview

This document provides the full **Solution Architecture** and documentation for the **Ticket Management System**, built using **Laravel (latest)**, **Inertia.js**, and **React**. It covers the system's architecture, installation guide, database design, entity relationships, and user operating procedures.

---

## 2. Solution Architecture

### 2.1 System Layers

| Layer | Description |
|-------|-------------|
| **Frontend (React + Inertia.js)** | Manages UI, routing, and communication with Laravel controllers. |
| **Backend (Laravel)** | Handles business logic, authentication, and database operations. |
| **Database (MySQL)** | Stores user and ticket data, ensuring data integrity. |
| **Security Layer** | Uses Gates and Middleware to control access and visibility. |

### 2.2 Request Flow

```
User (Browser)
   ↓
React + Inertia.js Frontend
   ↓
Laravel Controller (TicketController, UserController)
   ↓
Model (Ticket, User)
   ↓
Database (MySQL)
   ↓
Response Rendered via Inertia (React View)
```

### 2.3 Key Security Features
- **Middleware:** `auth`, `admin`, `ensure.ticket.visible`
- **Gates:** Controls who can update or modify tickets
- **Soft Deletes:** Tickets can be safely deleted and restored

---

## 3. Installation Manual

### 3.1 Prerequisites
- PHP >= 8.2
- Composer
- Node.js & npm
- MySQL or MariaDB
- Laravel CLI

### 3.2 Steps

```bash
# 1. Clone the repository
git clone https://github.com/<your-username>/<repo-name>.git
cd repo-name

# 2. Install dependencies
composer install
npm install

# 3. Copy environment file
cp .env.example .env

# 4. Configure .env (Database and App URL)

# 5. Generate key
php artisan key:generate

# 6. Run migrations
php artisan migrate

# 7. Build frontend assets
npm run dev

# 8. Start development server
php artisan serve
```

Access app: [http://127.0.0.1:8000](http://127.0.0.1:8000)

---

## 4. Database Layout

### 4.1 Tables Overview

#### Table: `users`
| Column | Type | Description |
|---------|------|-------------|
| id | BIGINT | Primary key |
| name | VARCHAR(255) | User's name |
| email | VARCHAR(255) | Unique email |
| password | VARCHAR(255) | Hashed password |
| is_admin | BOOLEAN | Role flag |
| created_at | TIMESTAMP | Record created time |
| updated_at | TIMESTAMP | Record updated time |

#### Table: `tickets`
| Column | Type | Description |
|---------|------|-------------|
| id | BIGINT | Primary key |
| name | VARCHAR(255) | Ticket title |
| description | TEXT | Ticket details |
| status | ENUM | ['pending', 'inprogress', 'completed', 'onhold'] |
| image_path | VARCHAR(255) | Uploaded image path |
| created_by | BIGINT (FK) | Linked to `users.id` |
| assigned_to | BIGINT (FK, NULLABLE) | Linked to `users.id` |
| assigned_at | TIMESTAMP | When assigned |
| completed_at | TIMESTAMP | When completed |
| deleted_at | TIMESTAMP | For soft delete |
| created_at | TIMESTAMP | Created time |
| updated_at | TIMESTAMP | Updated time |

### 4.2 Relationships
- **User → Tickets (created_by)** → 1:N
- **User → Tickets (assigned_to)** → 1:N
- **Ticket → User** (belongsTo both author and assignee)

### 4.3 ER Diagram (Text View)
```
+---------------------+             +----------------------+
|       users         |             |       tickets        |
+---------------------+             +----------------------+
| id (PK)             |<----+    +->| id (PK)              |
| name                |     |    |  | name                |
| email               |     |    |  | description         |
| is_admin            |     |    |  | status              |
| created_at          |     |    |  | created_by (FK)     |
| updated_at          |     |    |  | assigned_to (FK)    |
+---------------------+     |    |  | assigned_at         |
                            |    |  | completed_at        |
                            |    |  | deleted_at          |
                            |    |  +----------------------+
                            |    |
          (created_by)------+    +------(assigned_to)
```

---

## 5. Standard Operating Procedures (User Manual)

### 5.1 Accessing the System
1. Go to [http://127.0.0.1:8000](http://127.0.0.1:8000)
2. You’ll be redirected to the **Login** page.
3. Click **Register** to create a new account.

### 5.2 Roles and Permissions
| Role | Permissions |
|------|--------------|
| **Admin** | View all tickets, assign, update, and delete any ticket. Access user list. |
| **User** | Create and view only own tickets or assigned tickets. Update status of assigned tickets. |

### 5.3 Ticket Management
- **Create Ticket**: Add a title, description, and upload image.
- **Assign Ticket**: Admin can assign tickets to users.
- **Update Status**: Assignee can change to `inprogress` or `completed`.
- **Delete Ticket**: Author or Admin can delete.

### 5.4 Navigation
| Page | Description |
|------|-------------|
| Dashboard | Overview of your tickets |
| My Tickets | Lists tickets you created or are assigned to |
| All Tickets (Admin) | Shows all tickets in system |
| Users (Admin) | Displays all registered users |

### 5.5 Logout
Click the top-right dropdown → **Logout**.

---

## 6. Project Folder Structure
```
app/
 ├── Http/
 │   ├── Controllers/ (TicketController, UserController)
 │   ├── Middleware/ (AdminMiddleware, EnsureTicketVisible)
 │   └── Requests/ (StoreTicketRequest, UpdateTicketRequest)
 ├── Models/ (User.php, Ticket.php)
resources/
 ├── js/
 │   ├── Pages/ (Tickets/, Users/, Auth/)
 │   └── Layouts/
database/
 ├── migrations/
 └── seeders/
```

---

## 7. Developer Notes
- Middleware: `admin`, `ensure.ticket.visible`
- Policy management handled in `AuthServiceProvider`
- Uses Laravel soft deletes and pagination
- Frontend is built using Inertia + React + TailwindCSS

---

## 8. License
MIT © 2025 — Developed by [Your Name]

