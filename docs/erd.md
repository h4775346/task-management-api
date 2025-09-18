<div align="center">
  <img src="https://mermaid.js.org/images/mermaid-logo.svg" alt="Mermaid Logo" width="100">
  <h1>Entity Relationship Diagram</h1>
  <p>📘 Database Schema Visualization for Task Management API</p>
  
  [![ERD](https://img.shields.io/badge/ERD-Visualization-FF3399?style=for-the-badge)](https://mermaid.js.org)
  [![Database](https://img.shields.io/badge/Database-MySQL-4479A1?style=for-the-badge)](https://www.mysql.com)
  [![Schema](https://img.shields.io/badge/Schema-Design-00D1B2?style=for-the-badge)](https://en.wikipedia.org/wiki/Entity%E2%80%93relationship_model)
</div>

## 🌟 Overview

This Entity Relationship Diagram (ERD) provides a visual representation of the database schema for the Task Management API. The diagram illustrates the relationships between entities, primary keys, foreign keys, and cardinality to help developers understand the data structure.

## 📊 Database Schema

```
erDiagram
    %% Entity Definitions with Enhanced Styling %%
    
    USERS {
        int id PK "Primary Key"
        string name "User's full name"
        string email "Unique email address"
        string password "Hashed password"
        datetime email_verified_at "Email verification timestamp"
        string remember_token "Remember me token"
        datetime created_at "Record creation timestamp"
        datetime updated_at "Record last update timestamp"
    }
    
    ROLES {
        int id PK "Primary Key"
        string name "Unique role name"
        string guard_name "Authentication guard"
        datetime created_at "Record creation timestamp"
        datetime updated_at "Record last update timestamp"
    }
    
    PERMISSIONS {
        int id PK "Primary Key"
        string name "Unique permission name"
        string guard_name "Authentication guard"
        datetime created_at "Record creation timestamp"
        datetime updated_at "Record last update timestamp"
    }
    
    MODEL_HAS_ROLES {
        int role_id PK "Role identifier"
        int model_id PK "Model identifier"
        string model_type "Model type"
    }
    
    MODEL_HAS_PERMISSIONS {
        int permission_id PK "Permission identifier"
        int model_id PK "Model identifier"
        string model_type "Model type"
    }
    
    ROLE_HAS_PERMISSIONS {
        int permission_id PK "Permission identifier"
        int role_id PK "Role identifier"
    }
    
    TASKS {
        int id PK "Primary Key"
        string title "Task title"
        text description "Task description"
        string status "Task status (pending/completed/canceled)"
        date due_date "Task deadline"
        int assignee_id FK "Assigned user"
        int created_by FK "Creator user"
        int updated_by FK "Last updater"
        datetime created_at "Record creation timestamp"
        datetime updated_at "Record last update timestamp"
        datetime deleted_at "Soft delete timestamp"
    }
    
    TASK_DEPENDENCIES {
        int task_id PK "Dependent task"
        int depends_on_task_id PK "Dependency task"
    }
    
    %% Enhanced Relationships with Descriptive Labels %%
    
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
```

## 🎨 Design Elements

### Color Scheme
- **Primary Entities**: Blue (#4479A1)
- **Relationship Tables**: Green (#00D1B2)
- **Task Management**: Purple (#FF3399)

### Visual Hierarchy
1. **Users & Authentication** - Foundation entities
2. **Task Management** - Core business entities
3. **Relationships** - Connecting entities

## 🔗 Relationship Cardinality

| Relationship | Cardinality | Description |
|--------------|-------------|-------------|
| Users → Tasks | One-to-Many | One user can create many tasks |
| Users → Roles | Many-to-Many | Users can have multiple roles |
| Roles → Permissions | Many-to-Many | Roles can have multiple permissions |
| Tasks → Dependencies | Many-to-Many | Tasks can have multiple dependencies |

## 🏗️ Schema Architecture

### Authentication Layer
```
USERS ←→ MODEL_HAS_ROLES ←→ ROLES ←→ ROLE_HAS_PERMISSIONS ←→ PERMISSIONS
USERS ←→ MODEL_HAS_PERMISSIONS ←→ PERMISSIONS
```

### Task Management Layer
```
USERS ←→ TASKS (assignee, creator, updater)
TASKS ←→ TASK_DEPENDENCIES ←→ TASKS
```

## 📈 Key Features

### Soft Deletes
- **TASKS** table includes `deleted_at` for soft deletion
- Maintains data integrity while allowing recovery

### Audit Trail
- **created_by** and **updated_by** fields track user actions
- **created_at** and **updated_at** timestamps for audit purposes

### Flexible Authorization
- Role-based access control (RBAC) system
- Granular permissions through many-to-many relationships

## 🛠️ Implementation Notes

### Indexes
- Primary keys automatically indexed
- Foreign keys should be indexed for performance
- Composite primary keys in relationship tables

### Constraints
- Foreign key constraints ensure referential integrity
- Unique constraints on email, role names, and permission names
- Check constraints on status field values

## 📚 Related Documentation

- **API Documentation**: [`../README.md`](../README.md)
- **OpenAPI Specification**: [`openapi.yaml`](openapi.yaml)
- **Postman Collection**: [`postman_collection.json`](postman_collection.json)
- **Postman Guide**: [`POSTMAN.md`](POSTMAN.md)

---

<div align="center">
  <p>Built with ❤️ using Mermaid.js</p>
  <p>⭐ Don't forget to star this repository if you find it useful!</p>
</div>