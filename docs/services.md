# Kyurie Service Architecture Refactor Guide

## Objective

Refactor the service layer into a domain-oriented architecture while preserving existing behavior.

This refactor is structural only.

- Do NOT change business logic.
- Do NOT change routes.
- Do NOT change database schema.
- Do NOT introduce new features.
- Existing functionality must continue working exactly as before.

The goal is to improve maintainability, separation of concerns, and service reuse.

---

# High Level Architecture

The application should be divided into two service layers.

```
Controller
      │
      ▼
Application (Orchestrator)
      │
      ▼
Domain Services
      │
      ▼
Models
```

Controllers should never coordinate multiple domain services.

Controllers should only call a single orchestrator (when needed) or a single domain service.

---

# Domain Services

A Domain Service owns business logic for one domain only.

Examples:

```
Game
Product
Payment
Voucher
Flashsale
Banner
Category
Target
Order
Auth
User
```

Each service should only know about its own aggregate.

Examples:

```
GameService
ProductService
PaymentService
FlashsaleService
BannerService
CategoryService
OrderService
```

A Domain Service may call:

- its own Model
- helper classes
- validation services
- utility services

A Domain Service SHOULD NOT coordinate unrelated domains.

Example:

Good:

GameService
-> GameModel

Bad:

GameService
-> PaymentService
-> VoucherService
-> BannerService

```

---

# Application / Orchestrator Services

An orchestrator represents one use case or page.

Examples:

```

HomeService
GameDetailService
CheckoutService
ProfileService
SearchService
DashboardService

```

An orchestrator should not contain business rules.

Its responsibility is to coordinate multiple domain services.

Example:

```

HomeService

BannerService
GameService
FlashsaleService
CategoryService

```

Example:

```

GameDetailService

GameService
ProductService
PaymentService
TargetService
VoucherService

```

The orchestrator prepares data for controllers/views.

---

# Controllers

Controllers must remain thin.

Avoid:

```

Controller

GameService
ProductService
VoucherService
PaymentService
FlashsaleService

```

Prefer:

```

Controller

GameDetailService

```

or

```

Controller

HomeService

```

Controllers should not build page data.

---

# Models

Each model belongs to one table or aggregate.

Avoid generic models like:

```

HomeModel
DashboardModel
WebsiteModel

```

Prefer:

```

GameModel
BannerModel
CategoryModel
FlashsaleModel
ProductModel
PaymentModel
VoucherModel
OrderModel

```

Each model should only query its own data.

Cross-domain composition belongs in services.

---

# Refactoring Home

Current

```

HomeModel

getBanner()
getFlashsale()
getPopularGames()
getCategories()

```

Target

```

BannerService
-> BannerModel

FlashsaleService
-> FlashsaleModel

GameService
-> GameModel

CategoryService
-> CategoryModel

HomeService
-> BannerService
-> FlashsaleService
-> GameService
-> CategoryService

```

HomeService becomes an orchestrator only.

---

# Refactoring Game Detail

Current architecture is already close.

```

GameDetailService

GameService
ProductService
PaymentService
TargetService

```

Continue using this pattern.

Do not move business logic into GameDetailService.

Business logic stays inside domain services.

---

# Folder Structure

Target:

```

app/
Services/

        Banner/
            BannerService.php

        Category/
            CategoryService.php

        Flashsale/
            FlashsaleService.php

        Game/
            GameService.php
            GamePriceService.php
            GameImportService.php
            GameSearchService.php

        Order/
            OrderService.php
            CheckoutValidationService.php

        Payment/
            PaymentService.php
            PaymentFeeService.php

        Product/
            ProductService.php

        Target/
            TargetService.php

        Voucher/
            VoucherService.php

        Home/
            HomeService.php

        GameDetail/
            GameDetailService.php

        Checkout/
            CheckoutService.php

```

The exact folder names may vary, but services must be grouped by responsibility.

---

# Dependency Rules

Allowed:

```

Controller
-> Orchestrator

Controller
-> Domain Service

```

Allowed:

```

Orchestrator
-> Domain Service

```

Allowed:

```

Domain Service
-> Model

```

Avoid:

```

Model
-> Service

```

Avoid:

```

Controller
-> 6 Services

```

Avoid:

```

Domain Service
-> unrelated Domain Services

```

Unless absolutely required.

---

# Business Logic

Business logic belongs to the domain.

Examples:

GameService

- active game lookup
- search
- slug lookup
- game mapping

FlashsaleService

- stock validation
- stock consumption
- flashsale lookup

VoucherService

- voucher validation
- discount calculation

PaymentService

- payment availability
- fee calculation

PriceService

- final price
- formatting
- tax
- fee

Do not move these rules into controllers.

---

# Orchestrator Responsibilities

An orchestrator should only:

- load required services
- coordinate calls
- prepare page DTO/array
- return view data

It should NOT:

- query models directly
- duplicate business rules
- calculate prices
- validate vouchers
- implement stock logic

---

# Refactor Strategy

Perform the refactor incrementally.

Priority:

1. Extract HomeModel responsibilities into domain services.
2. Convert HomeService into an orchestrator.
3. Keep GameDetailService as orchestrator.
4. Remove duplicated logic.
5. Keep controllers thin.
6. Verify no behavior changes after every step.

Never perform a massive rewrite in one commit.

Small safe commits only.

---

# Coding Principles

- Single Responsibility Principle
- Domain-oriented services
- Thin controllers
- Reusable business logic
- Composition over duplication
- Preserve existing behavior
```
