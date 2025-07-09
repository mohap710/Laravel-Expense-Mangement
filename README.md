# Laravel Expenses API Module

This repository contains a Laravel API module for managing user expenses. It demonstrates a modular approach to building Laravel applications, utilizing a Service Layer for business logic, API Resources for data transformation, Form Requests for validation, and Scribe for automatic API documentation.

## Table of Contents

-   [Setup Instructions](#1-setup-instructions)
-   [Overview of Structure and Decisions](#2-overview-of-structure-and-decisions)
-   [Assumptions Made](#3-assumptions-made)
-   [Time Spent](#4-time-spent)
-   [Functionality](#5-functionality)

---

## 1. Setup Instructions

Follow these steps to get the project up and running on your local machine.

### Prerequisites

-   PHP >= 8.2
-   Composer
-   Node.js & npm (optional, for frontend assets)
-   MySQL or PostgreSQL database (or rely on the included SQLite for quick setup)

### Installation Steps

1.  **Clone the repository:**

    ```bash
    git clone https://github.com/mohap710/Laravel-Expense-Mangement.git
    cd Laravel-Expense-Mangement
    ```

2.  **Install Composer dependencies:**

    ```bash
    composer install
    ```

3.  **Update Composer Autoloading:**
    This is crucial for recognizing classes in the `Modules/` directory.

    ```bash
    composer dump-autoload
    ```

4.  **Create and configure your `.env` file:**

    ```bash
    cp .env.example .env
    ```

    Open `.env` and configure your database connection. By default, it's set up for SQLite if you prefer that for simplicity. If using MySQL/PostgreSQL, update the `DB_*` variables.

    ```dotenv
    DB_CONNECTION=sqlite # Or mysql, pgsql etc.
    # If using SQLite, ensure a database file exists (e.g., database/database.sqlite)
    # The database/database.sqlite file might be included in this repo for quick setup.
    # If starting fresh or using another DB:
    # DB_HOST=127.0.0.1
    # DB_PORT=3306
    # DB_DATABASE=your_database_name
    # DB_USERNAME=your_db_username
    # DB_PASSWORD=your_db_password
    ```

5.  **Generate Application Key:**

    ```bash
    php artisan key:generate
    ```

6.  **Run Database Migrations:**
    This will create the `expenses` table. If a `database/database.sqlite` file is already present and populated, this step might not be strictly necessary for initial testing, but it's essential for a clean setup.

    ```bash
    php artisan migrate
    ```
7.  **Generate API Documentation:**
    This will create the HTML documentation in `public/docs` and OpenAPI JSON in `storage/docs`.

    ```bash
    php artisan scribe:generate
    ```

    You can then view the documentation by visiting `http://127.0.0.1:8000/docs` in your browser.

8.  **Start the Laravel Development Server:**

    ```bash
    php artisan serve
    ```

    Your application will be accessible at `http://127.0.0.1:8000`.



---

## 2. Overview of Structure and Decisions

This project is structured with a focus on modularity and clean architecture, particularly for the Expenses API.

-   **Modular Design (`Modules/Expenses`):** The core logic for expenses is encapsulated within the `Modules/Expenses` directory. This promotes separation of concerns, making the codebase easier to manage, scale, and test.

    -   **`Modules/Expenses/Models/Expense.php`**: The Eloquent model for the `expenses` table, configured to use UUIDs as primary keys.

    -   **`Modules/Expenses/Providers/RouteServiceProvider.php`**: A dedicated service provider for the Expenses module. It's responsible for defining and loading only the API routes specific to this module, ensuring they are properly prefixed (`/api`) and use the `api` middleware. This service provider is registered within the `Modules/Expenses/Providers/ExpenseServiceProvider.php`.

    -   **`Modules/Expenses/routes/api.php`**: Contains the API resource routes for the Expense entity (`Route::apiResource('expenses', ExpenseController::class)`).

    -   **`Modules/Expenses/Http/Controllers/ExpenseController.php`**: Handles incoming API requests for expense management. It's kept lean by delegating complex business logic to the `ExpenseService`. It also includes Scribe annotations for documentation.

    -   **`Modules/Expenses/Http/Requests/CreateExpenseRequest.php` (Assumed) & `UpdateExpenseRequest.php`**: Form Request classes for handling validation of incoming data. They define validation rules and can include a `bodyParameters()` method for Scribe to generate richer documentation.

    -   **`Modules/Expenses/Http/Resources/ExpenseResource.php`**: An API Resource for transforming Expense model data into a consistent JSON format for API responses.

    -   **`Modules/Expenses/Services/ExpenseService.php` (Assumed)**: A Service Layer class (not explicitly provided in all snippets, but implied by `ExpenseController`'s usage) that encapsulates the business logic for expense operations, such as applying filters, handling data creation/updates, and interacting with the `Expense` model.

-   **Service Layer:** The `ExpenseController` delegates business logic to an `ExpenseService`. This keeps controllers thin and focused on request/response handling, while centralizing complex operations and improving testability.

-   **API Resources:** `ExpenseResource` ensures consistent and well-formatted JSON responses for the API, abstracting away the underlying database structure.

-   **Form Requests:** Used for robust and reusable request validation, keeping controller methods clean.

-   **Scribe Integration:** Scribe annotations are used directly in the `ExpenseController` and `FormRequest` classes to automatically generate comprehensive and interactive API documentation. This includes details on endpoints, parameters (query, body, URL), and example responses.

-   **UUIDs for Primary Keys:** Using UUIDs for the `id` column provides a more distributed and less predictable primary key, which can be beneficial in distributed systems and for security.

---

## 3. Assumptions Made

-   Laravel Version: This project assumes Laravel 11 or Laravel 12, given the bootstrap/app.php structure and the absence of a default RouteServiceProvider.php in the app/Providers directory.

-   PHP Version: PHP 8.2 or higher is assumed based on modern Laravel requirements.

-   Database: While it can work with MySQL/PostgreSQL, it's configured for SQLite by default for easier assessment setup. A pre-migrated database/database.sqlite file is included in the repository for immediate use.

---

## 4. Time Spent

Approximately 6 hours were spent on setting up the core structure, implementing the CRUD operations, filtering, API resources, and Scribe documentation.
A significant portion of this time was dedicated to understanding and integrating Scribe, as it was a new tool for this project.

---

## 5. Functionality

The Expenses API module provides the following functionalities:

-   **Create Expense:**

    -   **Endpoint:** `POST /api/expenses`
    -   **Description:** Allows creation of a new expense record.
    -   **Parameters:** `title`, `amount`, `category`, `expense_date`, `notes` (optional).
    -   **Validation:** Ensures required fields are present, `amount` is numeric, `category` is valid (from a predefined list), and `expense_date` is a valid date.

-   **View All Expenses:**

    -   **Endpoint:** `GET /api/expenses`
    -   **Description:** Retrieves a paginated list of all expense records.
    -   **Parameters:**
        -   `category` (optional): Filters expenses by a specific category (e.g., 'Food', 'Transportation').
        -   `start_date` (optional): Filters expenses from this date (inclusive), format `YYYY-MM-DD`.
        -   `end_date` (optional): Filters expenses up to this date (inclusive), format `YYYY-MM-DD`.
    -   **Response:** Returns a JSON object containing `success`, `data` (list of expense records), and `message`.

-   **Update an Expense:**

    -   **Endpoint:** `PUT/PATCH /api/expenses/{expense}`
    -   **Description:** Updates an existing expense record identified by its UUID.
    -   **Parameters:** `title`, `amount`, `category`, `expense_date`, `notes` (all optional, but at least one must be provided).
    -   **Validation:** Validates provided fields against the same rules as creation, but allows partial updates.

-   **Delete an Expense:**
    -   **Endpoint:** `DELETE /api/expenses/{expense}`
    -   **Description:** Deletes a specific expense record by its UUID.
    -   **Response:** Returns a `200` status upon successful deletion.
