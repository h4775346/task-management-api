<div align="center">
  <img src="https://mermaid.js.org/favicon.svg" alt="Mermaid Logo" width="100">
  <h1>Entity Relationship Diagram</h1>
  <p>📘 Database Schema Visualization for Task Management API</p>
  
  <a href="https://mermaid.js.org">
    <img src="https://img.shields.io/badge/ERD-Visualization-FF3399?style=for-the-badge" alt="ERD Badge">
  </a>
  <a href="https://www.mysql.com">
    <img src="https://img.shields.io/badge/Database-MySQL-4479A1?style=for-the-badge" alt="MySQL Badge">
  </a>
  <a href="https://en.wikipedia.org/wiki/Entity%E2%80%93relationship_model">
    <img src="https://img.shields.io/badge/Schema-Design-00D1B2?style=for-the-badge" alt="Schema Badge">
  </a>
</div>

## 🌟 Overview

This Entity Relationship Diagram (ERD) provides a visual representation of the database schema for the Task Management API.

## 📊 Database Schema

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
}