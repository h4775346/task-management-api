# Task Management API - Postman Collection

This document provides instructions on how to use the Postman collection for the Task Management API.

## Setup Instructions

1. **Import the Collection**:
   - Open Postman
   - Click on "Import" in the top left corner
   - Select the `postman_collection.json` file from the `docs` directory
   - Click "Import"

2. **Configure Environment Variables**:
   - Create a new environment in Postman
   - Set the following variables:
     - `base_url`: The base URL of your API server (default: `http://localhost:8000/api`)
     - `access_token`: Leave empty initially, will be populated after login

3. **Authenticate**:
   - Run the "Auth > Login" request with valid credentials
   - Copy the `access_token` from the response
   - Set the `access_token` variable in your environment

## Collection Structure

The collection is organized into the following folders:

### Auth
Endpoints for authentication:
- **Login**: Authenticate a user and obtain JWT tokens
- **Refresh Token**: Refresh an expired access token
- **Logout**: Invalidate the current token
- **Get Current User**: Retrieve authenticated user information

### Tasks
Endpoints for task management:
- **Create Task**: Create a new task (manager only)
- **Get Task**: Retrieve a specific task
- **Update Task**: Update task details (manager only)
- **Delete Task**: Delete a task (manager only)
- **Update Task Status**: Update task status (manager or assignee)

### Task Dependencies
Endpoints for managing task dependencies:
- **Get Task Dependencies**: Retrieve all dependencies for a task
- **Add Task Dependency**: Add a dependency to a task (manager only)
- **Remove Task Dependency**: Remove a dependency from a task (manager only)

### Task Search
Endpoints for searching and filtering tasks:
- **Search Tasks**: Search tasks with various filters

## Example Users

The application comes with pre-seeded users for testing:

1. **Manager User**:
   - Email: `manager@example.com`
   - Password: `password`

2. **Regular User 1**:
   - Email: `user1@example.com`
   - Password: `password`

3. **Regular User 2**:
   - Email: `user2@example.com`
   - Password: `password`

## Role-Based Access Control

The API implements RBAC with the following permissions:

### Manager Role
- Can create, update, and delete tasks
- Can assign tasks to any user
- Can manage task dependencies
- Can view all tasks
- Can update status of any task

### User Role
- Can view tasks assigned to them
- Can update status of tasks assigned to them

## Common Workflows

### 1. Creating and Managing Tasks (Manager)
1. Login as manager
2. Create a new task
3. Assign the task to a user
4. Add dependencies if needed
5. Update task status as needed

### 2. Updating Task Status (User)
1. Login as regular user
2. View assigned tasks
3. Update status of assigned tasks

### 3. Searching Tasks
1. Login as either manager or user
2. Use the search endpoint with filters
3. Managers can search all tasks, users can only search their assigned tasks

## Response Formats

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

## Testing

To test the API:

1. Start the development server: `php artisan serve`
2. Run database migrations: `php artisan migrate`
3. Seed the database: `php artisan db:seed`
4. Import the Postman collection
5. Follow the workflows above

## Troubleshooting

### Authentication Issues
- Ensure you're using the correct credentials
- Check that the `access_token` variable is set correctly
- Refresh the token if it has expired

### Permission Errors
- Verify your user role
- Check that you're performing actions allowed for your role

### Dependency Errors
- Ensure you're not creating circular dependencies
- Check that dependent tasks are completed before completing a task