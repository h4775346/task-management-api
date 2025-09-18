# Entity Relationship Diagram

```mermaid
erDiagram
    USERS {
        int id PK
        string name
        string email
        string password
        datetime email_verified_at
        string remember_token
        datetime created_at
        datetime updated_at
    }
    
    ROLES {
        int id PK
        string name
        string guard_name
        datetime created_at
        datetime updated_at
    }
    
    PERMISSIONS {
        int id PK
        string name
        string guard_name
        datetime created_at
        datetime updated_at
    }
    
    MODEL_HAS_ROLES {
        int role_id PK
        int model_id PK
        string model_type
    }
    
    MODEL_HAS_PERMISSIONS {
        int permission_id PK
        int model_id PK
        string model_type
    }
    
    ROLE_HAS_PERMISSIONS {
        int permission_id PK
        int role_id PK
    }
    
    TASKS {
        int id PK
        string title
        text description
        string status
        date due_date
        int assignee_id FK
        int created_by FK
        int updated_by FK
        datetime created_at
        datetime updated_at
        datetime deleted_at
    }
    
    TASK_DEPENDENCIES {
        int task_id PK FK
        int depends_on_task_id PK FK
    }
    
    USERS ||--o{ TASKS : "assignee"
    USERS ||--o{ TASKS : "creator"
    USERS ||--o{ TASKS : "updater"
    USERS ||--o{ MODEL_HAS_ROLES : "has"
    USERS ||--o{ MODEL_HAS_PERMISSIONS : "has"
    ROLES ||--o{ MODEL_HAS_ROLES : "assigned"
    PERMISSIONS ||--o{ MODEL_HAS_PERMISSIONS : "assigned"
    ROLES ||--o{ ROLE_HAS_PERMISSIONS : "has"
    PERMISSIONS ||--o{ ROLE_HAS_PERMISSIONS : "granted"
    TASKS ||--o{ TASK_DEPENDENCIES : "depends"
    TASKS ||--o{ TASK_DEPENDENCIES : "dependent"
```