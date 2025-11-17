# Driving License Management System

A full-stack web application built from scratch in PHP, designed to manage the complete workflow of applying for and issuing a driving license. This system uses a custom MVC (Model-View-Controller) architecture and MySQL database to support multiple user roles, automate scheduling, and streamline the entire evaluation process.

## Key Features

* **Multi-Role Access Control:** Four distinct user roles with specific permissions:
    * **Driver (Applicant)**
    * **Admin**
    * **Medical Officer**
    * **Evaluator**
* **Role-Specific Dashboards:** Each user role gets a unique dashboard summarizing their tasks, statistics, and scheduled appointments.
* **End-to-End Application Tracking:** The system tracks the application status from `submitted` to `license_issued`.
* **Dynamic Slot Booking:** Admins can create medical and driving test slots. Drivers can then book available slots for their tests.
* **Online Evaluation:** Medical Officers and Evaluators get a queue of scheduled applicants and can submit evaluation results (Pass/Fail or scores) directly through the portal.
* **Automated License Generation:** Upon passing the driving test, a temporary license is automatically generated and issued, which the driver can then download.
* **Public Status Check:** An unauthenticated public page allows anyone to check an application's status using its reference ID.
* **Public License Verification:** A public-facing tool to verify the authenticity and validity of an issued license number.

## User Roles & Workflow

1.  **Driver (Applicant)**
    * Registers for a new account.
    * Submits an application for a specific license type.
    * Books an available medical test slot.
    * After passing the medical test, books a driving test slot.
    * Views application status, test results, and evaluation details.
    * Downloads the temporary license once issued.

2.  **Admin**
    * Views system-wide statistics (total applications, pending tests, licenses issued).
    * Manages medical and driving test slots (create, delete, toggle availability).
    * Views and manages all applications, users, and issued licenses in the system.

3.  **Medical Officer**
    * Views a dashboard of scheduled medical appointments.
    * Submits a "Pass" or "Fail" result for an applicant's medical evaluation (vision, hearing, physical).
    * Views their own evaluation history.

4.  **Evaluator**
    * Views a dashboard of scheduled driving tests.
    * Submits a detailed score (0-100) for various driving criteria (vehicle control, traffic rules, parking). The system calculates the final result based on a passing score.
    * Views their own evaluation history.

## Tech Stack

* **Backend:** PHP (Custom MVC architecture)
* **Database:** MySQL
* **Frontend:** HTML5, CSS3, JavaScript, Bootstrap 5
* **Server:** Apache (or any server supporting PHP & `.htaccess`)

## Setup and Installation

1.  **Database Setup:**
    * Create a new MySQL database.
    * Import the `database_schema.sql` file to create all necessary tables and insert the default admin user.

2.  **Configuration:**
    * Navigate to `app/config/config.php`.
    * Update the `DB_HOST`, `DB_USER`, `DB_PASS`, and `DB_NAME` constants with your database credentials.
    * Update the `BASE_URL` constant to point to your project's `public` directory (e.g., `http://localhost/Driving-License-System/public`).

3.  **Server Configuration (Apache):**
    * Ensure `mod_rewrite` is enabled.
    * The `public/.htaccess` file should handle all routing, directing requests to `public/index.php`.
    * Set the document root of your virtual host to the project's `public` directory.

4.  **Run:**
    * Access the `BASE_URL` in your browser.
    * Log in as the default admin (credentials in `database_schema.sql`):
        * **Username:** `admin`
        * **Password:** `password`
