<div align="center">
  <img src="https://laravel.com/img/logomark.min.svg" alt="Laravel Logo" width="100">
  <h1>Task Management API</h1>
  <p>🚀 A Modern REST API for Task Management with JWT Authentication & RBAC</p>
  
  [![Laravel](https://img.shields.io/badge/Laravel-11.x-red?style=for-the-badge&logo=laravel)](https://laravel.com)
  [![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php)](https://www.php.net)
  [![License](https://img.shields.io/badge/License-MIT-blue?style=for-the-badge)](LICENSE)
  [![Docker](https://img.shields.io/badge/Docker-Ready-2496ED?style=for-the-badge&logo=docker)](https://www.docker.com)
</div>

## 🌟 Overview

Welcome to the **Task Management API** - a robust, scalable, and beautifully designed RESTful API built with Laravel 11. This API provides comprehensive task management capabilities with enterprise-grade features including JWT authentication, Role-Based Access Control (RBAC), task dependencies with cycle detection, and intelligent caching.

Whether you're building a project management application, workflow automation system, or issue tracking platform, this API provides the foundation you need to get started quickly.

## 🚀 Key Features

| Feature | Description |
|--------|-------------|
| 🔐 **JWT Authentication** | Secure token-based authentication using `tymon/jwt-auth` |
| 👥 **Role-Based Access Control** | Fine-grained permissions with `spatie/laravel-permission` |
| 📋 **Task Management** | Full CRUD operations with soft deletes |
| 🔗 **Task Dependencies** | Create dependencies between tasks with automatic cycle detection |
| 🔍 **Advanced Search** | Powerful filtering and search capabilities with caching |
| 🐳 **Docker Support** | Production-ready containerized environment |
| 🧪 **Comprehensive Testing** | Full test coverage with PestPHP |
| 📖 **Rich Documentation** | OpenAPI 3.1, Postman Collection, and ERD |

## 🛠️ Technology Stack

- **Framework**: Laravel 11
- **Authentication**: tymon/jwt-auth
- **RBAC**: spatie/laravel-permission
- **Database**: MySQL 8.x
- **Caching**: Laravel FILE cache
- **Testing**: PestPHP
- **Code Quality**: Laravel Pint, Larastan

## 📋 Requirements

### For Local Development
- PHP 8.2+
- Composer
- MySQL 8.x or SQLite
- Node.js (for frontend assets)
- Git

### For Docker Development (Recommended)
- Docker & Docker Compose
- Git

## 📦 Installation

### 💻 Local Development Setup

1. **Clone the Repository**
   ```bash
   git clone https://github.com/h4775346/task-management-api.git
   cd task-management-api
   ```

2. **Install PHP Dependencies**
   ```bash
   composer install
   ```

3. **Install JavaScript Dependencies**
   ```bash
   npm install
   ```

4. **Configure Environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   php artisan jwt:secret
   ```
   
   Update your `.env` file with your database credentials:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=tasks
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

5. **Run Migrations and Seeders**
   ```bash
   php artisan migrate --seed
   ```

6. **Start the Development Server**
   ```bash
   php artisan serve
   ```
   
   The API will be available at `http://localhost:8000/api`

### 🐳 Docker Setup (Recommended)

1. **Clone the Repository**
   ```bash
   git clone https://github.com/h4775346/task-management-api.git
   cd task-management-api
   ```

2. **Start the Docker Environment**
   ```bash
   make up
   ```

3. **Run Migrations and Seeders**
   ```bash
   make migrate-seed
   ```

4. **Access the Application**
   - API: `http://localhost:8000/api`
   - Database: `localhost:3306` (MySQL)

## 🎯 Quick Start Guide

### 1. **Login with Demo Credentials**

We provide pre-seeded demo accounts for testing:

**Manager Account** (Full permissions):
```
Email: manager@example.com
Password: password
```

**User Accounts** (Limited permissions):
```
Email: user1@example.com
Password: password

Email: user2@example.com
Password: password
```

### 2. **Generate a JWT Token**

```
php artisan auth:demo-token manager@example.com
```

### 3. **Test the API**

```
curl -H "Authorization: Bearer YOUR_TOKEN_HERE" \
     http://localhost:8000/api/tasks/search \
     -X POST \
     -d '{"status":"pending","page":1,"per_page":10}' \
     -H "Content-Type: application/json"
```

## 📡 API Endpoints

### 🔐 Authentication
| Method | Endpoint | Description |
|--------|----------|-------------|
| `POST` | `/api/auth/login` | Login and get JWT token with user details and permissions |
| `POST` | `/api/auth/refresh` | Refresh JWT token |
| `POST` | `/api/auth/logout` | Logout and invalidate token |
| `GET` | `/api/auth/me` | Get current user info with roles and permissions |

### 📋 Tasks
| Method | Endpoint | Description |
|--------|----------|-------------|
| `POST` | `/api/tasks` | Create a new task (manager only) |
| `GET` | `/api/tasks/{id}` | Get a specific task |
| `PUT` | `/api/tasks/{id}` | Update a task (manager only) |
| `PATCH` | `/api/tasks/{id}/status` | Update task status (assignee or manager) |
| `DELETE` | `/api/tasks/{id}` | Delete a task (manager only) |

### 🔗 Task Dependencies
| Method | Endpoint | Description |
|--------|----------|-------------|
| `GET` | `/api/tasks/{id}/dependencies` | Get task dependencies |
| `POST` | `/api/tasks/{id}/dependencies` | Add a dependency (manager only) |
| `DELETE` | `/api/tasks/{id}/dependencies/{dependsOnId}` | Remove a dependency (manager only) |

### 🔍 Task Search
| Method | Endpoint | Description |
|--------|----------|-------------|
| `POST` | `/api/tasks/search` | Search and filter tasks with pagination |

## 👥 Roles & Permissions

### Manager Role
Managers have full control over the task management system:
- ✅ Create, update, and delete tasks
- ✅ Assign tasks to any user
- ✅ View all tasks in the system
- ✅ Update status of any task
- ✅ Manage task dependencies

### User Role
Regular users have limited permissions focused on their assigned tasks:
- ✅ View only tasks assigned to them
- ✅ Update status of their assigned tasks
- ❌ Cannot create or delete tasks
- ❌ Cannot manage task dependencies

## ⚡ Make Commands (Docker)

Streamline your development workflow with these helpful commands:

```bash
make up              # Start all services
make down            # Stop all services
make logs            # View container logs
make migrate-seed    # Run migrations and seeders
make test            # Run test suite
make stan            # Run static analysis
make lint            # Check code style
```

## 🧪 Testing

Ensure code quality and functionality with our comprehensive test suite:

```
# Run all tests
make test

# Or directly
./vendor/bin/pest

# Run specific test
./vendor/bin/pest tests/Feature/TaskTest.php
```

## 🔍 Code Quality

Maintain high code standards with automated tools:

### Static Analysis
```
# Run PHPStan analysis
make stan

# Or directly
./vendor/bin/phpstan analyse
```

### Code Style
```
# Check code style
make lint

# Or directly
./vendor/bin/pint --test

# Auto-fix code style issues
./vendor/bin/pint
```

## 📚 Documentation

Explore our comprehensive documentation:

- **OpenAPI 3.1 Specification**: [`docs/openapi.yaml`](docs/openapi.yaml)
- **Postman Collection**: [`docs/postman_collection.json`](docs/postman_collection.json)
- **Postman Collection Guide**: [`docs/POSTMAN.md`](docs/POSTMAN.md)
- **Entity Relationship Diagram**: [`docs/erd.md`](docs/erd.md)

### 📊 Database Schema Visualization

For a visual representation of the database structure, see our [Entity Relationship Diagram](docs/erd.md) or view it directly below:

```
erDiagram
    USERS {
        int id PK
        varchar name
        varchar email
        varchar password
        datetime email_verified_at
        varchar remember_token
        datetime created_at
        datetime updated_at
    }

    ROLES {
        int id PK
        varchar name
        varchar guard_name
        datetime created_at
        datetime updated_at
    }

    PERMISSIONS {
        int id PK
        varchar name
        varchar guard_name
        datetime created_at
        datetime updated_at
    }

    MODEL_HAS_ROLES {
        int role_id
        int model_id
        varchar model_type
    }

    MODEL_HAS_PERMISSIONS {
        int permission_id
        int model_id
        varchar model_type
    }

    ROLE_HAS_PERMISSIONS {
        int permission_id
        int role_id
    }

    TASKS {
        int id PK
        varchar title
        text description
        varchar status
        date due_date
        int assignee_id
        int created_by
        int updated_by
        datetime created_at
        datetime updated_at
        datetime deleted_at
    }

    TASK_DEPENDENCIES {
        int task_id
        int depends_on_task_id
    }

    %% Relationships
    USERS ||--o{ TASKS : "assigned_tasks"
    USERS ||--o{ TASKS : "created_tasks"
    USERS ||--o{ TASKS : "updated_tasks"
    USERS ||--o{ MODEL_HAS_ROLES : "user_roles"
    USERS ||--o{ MODEL_HAS_PERMISSIONS : "user_permissions"

    ROLES ||--o{ MODEL_HAS_ROLES : "role_assignments"
    PERMISSIONS ||--o{ MODEL_HAS_PERMISSIONS : "permission_assignments"
    ROLES ||--o{ ROLE_HAS_PERMISSIONS : "role_permissions"
    PERMISSIONS ||--o{ ROLE_HAS_PERMISSIONS : "granted_permissions"

    TASKS ||--o{ TASK_DEPENDENCIES : "task_dependencies"
    TASKS ||--o{ TASK_DEPENDENCIES : "dependent_tasks"

```


## ⚙️ Environment Variables

Key configuration options (see `.env.example` for full list):

| Variable | Description | Default |
|----------|-------------|---------|
| `DB_CONNECTION` | Database connection driver | mysql |
| `DB_HOST` | Database host | db |
| `DB_PORT` | Database port | 3306 |
| `DB_DATABASE` | Database name | tasks |
| `DB_USERNAME` | Database user | tasks |
| `DB_PASSWORD` | Database password | tasks |
| `CACHE_DRIVER` | Cache driver | file |
| `JWT_TTL` | JWT token TTL (seconds) | 900 (15 min) |
| `JWT_REFRESH_TTL` | Refresh token TTL (minutes) | 43200 (30 days) |
| `TASKS_SEARCH_CACHE_TTL` | Search cache TTL (seconds) | 60 |

## ☁️ Deployment Considerations

For production deployments, consider these recommendations:

1. **Caching**: Replace FILE cache with Redis for multi-instance deployments
2. **Database**: Use a production-ready MySQL instance with proper backups
3. **Security**: Never commit `.env` files to version control
4. **Performance**: Enable PHP opcache and nginx gzip compression
5. **Scaling**: Use a load balancer for multiple app instances
6. **Monitoring**: Implement application monitoring and logging

## 🤝 Contributing

We welcome contributions! Please follow these steps:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🌐 Repository

**GitHub**: [https://github.com/h4775346/task-management-api.git](https://github.com/h4775346/task-management-api.git)

---

<div align="center">
  <p>Built with ❤️ using Laravel</p>
  <p>⭐ Don't forget to star this repository if you find it useful!</p>
</div>