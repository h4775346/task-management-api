<div align="center">
  <img src="https://www.postman.com/_ar-assets/images/global/logo-horizontal-white.svg" alt="Postman Logo" width="200">
  <h1>Task Management API - Postman Collection</h1>
  <p>📦 Comprehensive API Testing Collection for Task Management</p>
  
  [![Postman](https://img.shields.io/badge/Postman-Ready-FF6C37?style=for-the-badge&logo=postman)](https://www.postman.com)
  [![API](https://img.shields.io/badge/API-REST-0055DA?style=for-the-badge)](https://www.postman.com)
  [![JWT](https://img.shields.io/badge/JWT-Authentication-000000?style=for-the-badge)](https://jwt.io)
</div>

## 🌟 Overview

This Postman collection provides a comprehensive set of pre-configured requests for testing and exploring the Task Management API. With organized folders, example requests, and detailed documentation, you can quickly get started with API testing and integration.

## 🚀 Quick Start

### 1. **Import the Collection**

Follow these simple steps to import the collection into Postman:

1. Open Postman
2. Click on **"Import"** in the top left corner
3. Select the [`postman_collection.json`](postman_collection.json) file from the `docs` directory
4. Click **"Import"**

### 2. **Configure Environment Variables**

Create a new environment in Postman with these variables:

| Variable | Value | Description |
|----------|-------|-------------|
| `base_url` | `http://localhost:8000/api` | The base URL of your API server |
| `access_token` | *(leave empty)* | JWT access token (populated after login) |

### 3. **Authenticate & Start Testing**

1. Run the **"Auth → Login"** request with valid credentials
2. Copy the `access_token` from the response
3. Set the `access_token` variable in your environment
4. Start exploring the API!

## 🔐 JWT Configuration

Before using the API, ensure that JWT keys are properly generated:

### Generate Application Key
```bash
php artisan key:generate
```

### Generate JWT Secret
```bash
php artisan jwt:secret
```

> **Note**: These commands should be run during the initial setup of the application. The JWT secret is required for token generation and validation.

## 📁 Collection Structure

The collection is organized into intuitive folders for easy navigation:

### 🔐 Auth
Endpoints for authentication and user management:
- **Login** - Authenticate a user and obtain JWT tokens
- **Refresh Token** - Refresh an expired access token
- **Logout** - Invalidate the current token
- **Get Current User** - Retrieve authenticated user information

### 📋 Tasks
Endpoints for task management:
- **Create Task** - Create a new task (manager only)
- **Get Task** - Retrieve a specific task
- **Update Task** - Update task details (manager only)
- **Delete Task** - Delete a task (manager only)
- **Update Task Status** - Update task status (manager or assignee)

### 🔗 Task Dependencies
Endpoints for managing task dependencies:
- **Get Task Dependencies** - Retrieve all dependencies for a task
- **Add Task Dependency** - Add a dependency to a task (manager only)
- **Remove Task Dependency** - Remove a dependency from a task (manager only)

### 🔍 Task Search
Endpoints for searching and filtering tasks:
- **Search Tasks** - Search tasks with various filters

## 👥 Example Users

The application comes with pre-seeded users for testing:

### Manager User (Full Permissions)
```
📧 Email: manager@example.com
🔑 Password: password
```

### Regular Users (Limited Permissions)
```
📧 Email: user1@example.com
🔑 Password: password

📧 Email: user2@example.com
🔑 Password: password
```

## 🔐 Role-Based Access Control

The API implements RBAC with distinct permission levels:

### Manager Role
<details>
<summary>📋 View Permissions</summary>

- ✅ Create, update, and delete tasks
- ✅ Assign tasks to any user
- ✅ Manage task dependencies
- ✅ View all tasks
- ✅ Update status of any task
</details>

### User Role
<details>
<summary>📋 View Permissions</summary>

- ✅ View tasks assigned to them
- ✅ Update status of their assigned tasks
- ❌ Cannot create or delete tasks
- ❌ Cannot manage task dependencies
</details>

## 🔄 Common Workflows

### 1. Creating and Managing Tasks (Manager)
<details>
<summary>📋 View Steps</summary>

1. Login as manager
2. Create a new task
3. Assign the task to a user
4. Add dependencies if needed
5. Update task status as needed
</details>

### 2. Updating Task Status (User)
<details>
<summary>📋 View Steps</summary>

1. Login as regular user
2. View assigned tasks
3. Update status of assigned tasks
</details>

### 3. Searching Tasks
<details>
<summary>📋 View Steps</summary>

1. Login as either manager or user
2. Use the search endpoint with filters
3. Managers can search all tasks, users can only search their assigned tasks
</details>

## 📦 Response Formats

All responses follow a consistent JSON format:

### Success Responses
```json
{
  "data": {...},
  "links": {...},
  "meta": {...}
}
```

### Error Responses
```json
{
  "message": "Error description"
}
```

## 🧪 Testing

To test the API:

1. Start the development server: `php artisan serve`
2. Run database migrations: `php artisan migrate`
3. Seed the database: `php artisan db:seed`
4. Generate JWT keys: `php artisan jwt:secret`
5. Import the Postman collection
6. Follow the workflows above

## 🛠️ Troubleshooting

### Authentication Issues
<details>
<summary>🔍 View Solutions</summary>

- Ensure you're using the correct credentials
- Check that the `access_token` variable is set correctly
- Refresh the token if it has expired
- Verify that JWT keys are properly generated
</details>

### Permission Errors
<details>
<summary>🔍 View Solutions</summary>

- Verify your user role
- Check that you're performing actions allowed for your role
</details>

### Dependency Errors
<details>
<summary>🔍 View Solutions</summary>

- Ensure you're not creating circular dependencies
- Check that dependent tasks are completed before completing a task
</details>

## 📚 Additional Resources

- **OpenAPI Specification**: [`openapi.yaml`](openapi.yaml)
- **Entity Relationship Diagram**: [`erd.md`](erd.md)
- **Main Documentation**: [`../README.md`](../README.md)

---

<div align="center">
  <p>Built with ❤️ for API Developers</p>
  <p>⭐ Don't forget to star this repository if you find it useful!</p>
</div>