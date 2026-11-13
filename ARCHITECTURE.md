# Architecture & Data Model

## Technology Stack Justification
1.  **PHP 8.x:** Chosen for the backend because of its excellent native file-handling capabilities (`$_FILES`, `finfo`) and ease of deployment on local event servers without requiring heavy build steps.
2.  **MySQL:** A relational database is perfect here. We need to strictly link `photos` to `users` using Foreign Keys with `ON DELETE CASCADE`, ensuring data integrity.
3.  **Vanilla JavaScript:** Chosen for the slideshow polling. Using React or Vue would be overkill for a single-page TV display. Native `fetch()` handles the real-time polling efficiently.

## Data Model

The application relies on two normalized MySQL tables:

### Table: `users`
*   `id` (INT, Primary Key)
*   `name` (VARCHAR)
*   `normalized_name` (VARCHAR) - Used for case-insensitive matching.
*   `visitor_token` (VARCHAR) - Stored in browser cookies for session tracking.
*   `visit_count` (INT) - Increments to determine "Returning" status.

### Table: `photos`
*   `id` (INT, Primary Key)
*   `user_id` (INT, Foreign Key -> users.id)
*   `stored_filename` (VARCHAR) - Cryptographically randomized name.
*   `original_filename` (VARCHAR) - The name the user uploaded.
*   `consent_given` (TINYINT) - Boolean flag.

## Data Flow Diagram

```text
[Mobile Phone]                    [Local XAMPP Server]                   [Smart TV Display]
--------------                    --------------------                   ------------------
 1. index.php   -- POST Data -->   2. upload.php                          
 (Name, Photo,                     - Validates MIME type
  Consent)                         - Checks visitor_token 
                                   - Saves photo to /uploads
                                            |
                                            v
                                   3. MySQL Database
                                   (users & photos tables)
                                            |
                                            | (Polled every 2s)
                                            v
                                   4. api_get_photos.php  <-- Fetch --    5. slideshow.php
                                   - Returns JSON payload                 - Updates DOM
                                   - Filters by consent=1                 - Rotates every 3s