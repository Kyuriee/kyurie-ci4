# Architecture

## Project Layers

```
HTTP Request
      │
      ▼
Controller
      │
      ▼
Application Services
      │
      ▼
Domain Services
      │
      ▼
Models
      │
      ▼
Database
```

## Layer Responsibilities

Controller

- HTTP only
- Validation
- Response

Application Service

- Use Case
- Orchestration

Domain Service

- Business Rules

Model

- Database Access

## Dependency Rules

Controller
→ Application Service

Application Service
→ Domain Service

Domain Service
→ Model

Forbidden

Model
→ Service

View
→ Service

View
→ Model

## Project Structure

app/

Controllers/
Models/
Services/
Views/
Config/

docs/

...
