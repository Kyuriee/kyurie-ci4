# AGENT.md

## Project Goal

Kyurie is a maintainable, domain-oriented CodeIgniter 4 application.

The architecture prioritizes:

- Long-term maintainability
- Reusable business logic
- Clear separation of responsibilities
- Consistent project structure

When making implementation decisions, prefer maintainability and consistency over short-term convenience.

# Kyurie CI4

This document defines the working rules for AI coding agents contributing to this project.

Before modifying any code, read the project documentation in the following order:

1. `docs/architecture.md`
2. `docs/services.md`
3. Any additional documentation related to the current task.

These documents define the project's architecture, service design, and engineering standards.

---

# General Rules

- Preserve existing behavior unless explicitly requested.
- Prefer incremental refactoring over large rewrites.
- Do not introduce new architectural patterns without approval.
- Follow the documented architecture and service conventions.
- Keep changes focused on the requested task.
- Avoid modifying unrelated code.

---

# Development Principles

Always prefer:

- Small, focused classes
- Thin controllers
- Reusable business logic
- Single Responsibility Principle
- Composition over duplication

Avoid:

- Fat controllers
- God classes
- Duplicate business logic
- Hidden side effects
- Cross-domain coupling

---

# Refactoring Rules

When refactoring:

- Preserve public behavior.
- Keep commits small and isolated.
- Do not change routes, database schema, or business logic unless requested.
- Prioritize maintainability over unnecessary abstraction.

---

# Before Finishing

Verify that:

- Existing functionality still works.
- No duplicated business logic was introduced.
- Architecture rules were respected.
- New code follows existing project conventions.

If documentation and existing implementation conflict, follow the documentation unless instructed otherwise.
