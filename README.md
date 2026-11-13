# Visitor Photo Slideshow

## Overview
The Visitor Photo Slideshow is a localized web application designed for live events. It allows venue guests to seamlessly upload photos from their mobile phones to a centralized server. Once uploaded, the photos are dynamically pushed to a live Smart TV or projection screen in real-time.

## Tech Stack
*   **Backend:** Native PHP 8.x
*   **Database:** MySQL (via XAMPP/phpMyAdmin)
*   **Frontend:** HTML5, CSS3 (Custom Glassmorphism UI), Vanilla JS (Fetch API)

## Setup and Run Instructions

### Prerequisites
*   XAMPP installed on Windows, OR LAMPP/Apache installed on Ubuntu.
*   Git installed on your machine.

### Local Setup
1. **Clone the Repository:**
   Navigate to your web server's root directory (`C:\xampp\htdocs` on Windows, or `/var/www/html` on Ubuntu) and clone the project:
   `git clone <YOUR_GITHUB_REPO_URL> visitor_slideshow`
2. **Initialize the Database:**
   * Start your Apache and MySQL services.
   * Open a browser and navigate to `http://localhost/phpmyadmin`.
   * Create a new database named exactly: `visitor_slideshow`.
   * Go to the **SQL** tab, paste the contents of `database.sql`, and click **Go**.
3. **Directory Permissions (Ubuntu Only):**
   Ensure PHP has write permissions to create the uploads directory:
   `sudo mkdir uploads && sudo chmod -R 777 uploads`

### How to Run the App
1. **Launch the TV Display:**
   Open a browser on your main presentation screen/Smart TV and navigate to:
   `http://localhost/visitor_slideshow/slideshow.php` (Press **F11** for fullscreen).
2. **Launch the Guest Upload Portal:**
   Have guests connect to the local Wi-Fi and navigate to the portal using your computer's local IP address, or locally via:
   `http://localhost/visitor_slideshow/index.php`
3. **Usage:**
   Guests enter their name, select photos, and check the public display consent box. The TV screen automatically detects new uploads and transitions to them every 3 seconds.

## Deployed Link
*Note: This application is designed to run locally on a venue's intranet via XAMPP for maximum upload speed and zero internet latency. Therefore, no public URL is available.*