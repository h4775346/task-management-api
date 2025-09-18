# Task Management API

A REST API for task management with JWT authentication, RBAC, task dependencies with cycle detection, and caching.

## Features

- **Authentication**: JWT-based authentication using `tymon/jwt-auth`
- **Authorization**: Role-Based Access Control using `spatie/laravel-permission`
- **Task Management**: Create, read, update, delete tasks with soft deletes
- **Task Dependencies**: Add dependencies between tasks with cycle detection
- **Search & Filtering**: Advanced search with caching
- **Docker**: Production-ready Docker setup with nginx, PHP-FPM, and MySQL
- **Testing**: Comprehensive test suite using PestPHP
- **Documentation**: OpenAPI 3.1 specification, Postman collection, and ERD

## Requirements

- PHP 8.2+
- Composer
- Docker & Docker Compose
- Node.js (for frontend assets)

## Tech Stack

- **Framework**: Laravel 11
- **Authentication**: tymon/jwt-auth
- **RBAC**: spatie/laravel-permission
- **Database**: MySQL 8.x
- **Caching**: Laravel FILE cache (no Redis)
- **Testing**: PestPHP
- **QA**: Laravel Pint, Larastan
- **Documentation**: OpenAPI 3.1, Postman

## Quick Start

1. **Start the Docker environment**:
   ```bash
   make up
   ```

2. **Run migrations and seeders**:
   ```bash
   make migrate-seed
   ```

3. **Login with demo credentials**:
   ```bash
   # Manager account
   email: manager@example.com
   password: password
   
   # User accounts
   email: user1@example.com
   password: password
   
   email: user2@example.com
   password: password
   ```

4. **Generate a JWT token for testing**:
   ```bash
   php artisan auth:demo-token manager@example.com
   ```

5. **Test the API**:
   ```bash
   curl -H "Authorization: Bearer YOUR_TOKEN_HERE" \
        http://localhost:8000/api/tasks/search \
        -X POST \
        -d '{"status":"pending","page":1,"per_page":10}' \
        -H "Content-Type: application/json"
   ```

## Docker Setup

The project includes a complete Docker environment with:

- **app**: PHP 8.2 FPM container
- **web**: Nginx web server
- **db**: MySQL 8.x database

### Make Commands

- `make up` - Start all services
- `make down` - Stop all services
- `make logs` - View container logs
- `make migrate-seed` - Run migrations and seeders
- `make test` - Run test suite
- `make stan` - Run static analysis
- `make lint` - Check code style

## API Endpoints

### Authentication

- `POST /api/auth/login` - Login and get JWT token
- `POST /api/auth/refresh` - Refresh JWT token
- `POST /api/auth/logout` - Logout and invalidate token
- `GET /api/auth/me` - Get current user info

### Tasks

- `POST /api/tasks` - Create a new task (manager only)
- `GET /api/tasks/{id}` - Get a specific task
- `PUT /api/tasks/{id}` - Update a task (manager only)
- `PATCH /api/tasks/{id}/status` - Update task status (assignee or manager)
- `DELETE /api/tasks/{id}` - Delete a task (manager only)

### Task Dependencies

- `GET /api/tasks/{id}/dependencies` - Get task dependencies
- `POST /api/tasks/{id}/dependencies` - Add a dependency (manager only)
- `DELETE /api/tasks/{id}/dependencies/{dependsOnId}` - Remove a dependency (manager only)

### Task Search

- `POST /api/tasks/search` - Search and filter tasks with pagination

## Roles & Permissions

### Manager Role

- Can create, update, and delete tasks
- Can assign tasks to users
- Can view all tasks
- Can update status of any task

### User Role

- Can view only assigned tasks
- Can update status of assigned tasks only

## Caching

The task search endpoint uses Laravel FILE cache with versioned keys. Cache is automatically invalidated when:

- Tasks are created, updated, or deleted
- Task dependencies are added or removed

**Note**: FILE cache is safe for single-container deployments. For multi-instance deployments, use Redis or disable caching.

## Testing

Run the test suite with:

```bash
make test
```

Or directly:

```bash
./vendor/bin/pest
```

## Code Quality

### Static Analysis

Run PHPStan for static analysis:

```bash
make stan
```

Or directly:

```bash
./vendor/bin/phpstan analyse
```

### Code Style

Check code style with Laravel Pint:

```bash
make lint
```

Or directly:

```bash
./vendor/bin/pint --test
```

Fix code style issues:

```bash
./vendor/bin/pint
```

## Documentation

- **OpenAPI 3.1**: See `docs/openapi.yaml`
- **Postman Collection**: See `docs/postman_collection.json`
- **ERD**: See `docs/erd.md`

## Environment Variables

Key environment variables (see `.env.example` for full list):

- `DB_CONNECTION` - Database connection (mysql)
- `DB_HOST` - Database host (db)
- `DB_PORT` - Database port (3306)
- `DB_DATABASE` - Database name (tasks)
- `DB_USERNAME` - Database user (tasks)
- `DB_PASSWORD` - Database password (tasks)
- `CACHE_DRIVER` - Cache driver (file)
- `JWT_TTL` - JWT token TTL in seconds (900 = 15 minutes)
- `JWT_REFRESH_TTL` - JWT refresh token TTL in minutes (43200 = 30 days)
- `TASKS_SEARCH_CACHE_TTL` - Task search cache TTL in seconds (60)

## Deployment Considerations

1. **Caching**: For multi-instance deployments, replace FILE cache with Redis
2. **Database**: Use a production-ready MySQL instance
3. **Security**: Never commit `.env` files to version control
4. **Performance**: Enable PHP opcache and nginx gzip compression
5. **Scaling**: Use a load balancer for multiple app instances

## License

MIT License. See [LICENSE](LICENSE) file for details.