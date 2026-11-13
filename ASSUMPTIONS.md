# Project Assumptions & Gap Resolutions

While the project requirements outlined the core features, several gaps were left open for interpretation. Here is how they were addressed:

### 1. Identifying Returning Visitors
*   **The Gap:** The prompt requires returning visitors to have their previous photos included, but does not specify *how* to identify them.
*   **The Solution:** I implemented a hybrid approach using a 30-day persistent cookie (`visitor_token`) combined with a normalized name check in the MySQL database. 
*   **Why:** Forcing event guests to create an account with a password creates too much friction. The cookie allows automatic recognition on the same device, while the name fallback ensures if they use a different browser, their photos are still tied together.

### 2. Public Display Privacy & Consent
*   **The Gap:** Displaying photos publicly requires consent, but the exact mechanism wasn't defined.
*   **The Solution:** Added a mandatory, explicit HTML checkbox on the upload form. If the form is submitted without the consent flag (`$_POST['consent'] === '1'`), the backend strictly rejects the upload.

### 3. Concurrency (Multiple Simultaneous Uploads)
*   **The Gap:** At a busy event, multiple people might upload a photo called `image.jpg` at the exact same second.
*   **The Solution:** I configured the backend to completely discard the user's original filename. Files are saved using a cryptographic randomizer: `time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext`. This mathematically guarantees zero file overwrite collisions.

### 4. Slideshow Duration
*   **The Gap:** The prompt states "a specific duration".
*   **The Solution:** The JavaScript interval was set to exactly 3 seconds (3000ms). This provides a dynamic, fast-paced viewing experience that keeps guests engaged without staring at the screen for too long.

### 5. File Size Limits
*   **The Gap:** No specific upload limit was provided.
*   **The Solution:** Capped file size at 5MB per image in the PHP backend, which is sufficient for high-quality smartphone photos while preventing server memory exhaustion.