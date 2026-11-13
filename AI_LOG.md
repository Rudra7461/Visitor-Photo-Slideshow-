# AI Collaboration Log

This log documents key interactions with the LLM (Claude / Gemini) while building the application, including architectural prompts and critical security corrections.

1.  **Prompt:** "Design an SQL database schema for an event photo slideshow where guests submit photos. Include a way to track returning visitors without passwords."
    *   *Result:* The AI generated the two-table relational structure (`users` and `photos`) using a token-based system.
2.  **Prompt (Security Correction 1):** "Write the PHP backend to process the file uploads and validate the file types."
    *   *Where the AI was wrong:* The AI initially used `$_FILES['photos']['type']` to check if the uploaded file was an image.
    *   *How I noticed:* I knew from security best practices that client-side MIME headers can easily be spoofed (e.g., uploading a `.php` file disguised as an image header to execute a reverse shell).
    *   *How I fixed it:* I instructed the AI to rewrite the validation using PHP's `finfo_open(FILEINFO_MIME_TYPE)` to read the actual magic bytes of the file on the server side before saving it.
3.  **Prompt:** "Create a live updating frontend for the Smart TV display using Vanilla JS."
    *   *Result:* The AI provided the `setInterval` logic with the Fetch API to poll the PHP backend asynchronously.
4.  **Prompt:** "Write the api_get_photos.php endpoint to feed JSON data to the JS slideshow."
    *   *Result:* The AI successfully wrote the PDO query joining the users and photos tables to include the 'Returning Visitor' badge logic.
5.  **Prompt (Logic Correction 2):** "Update upload.php to handle a scenario where a second user uploads photos on the same machine."
    *   *Where the AI was wrong:* The AI wrote the logic to simply read the existing cookie and append the photos to it, ignoring the new name typed into the input field. 
    *   *How I noticed:* When testing locally on a single browser, changing the name to "Bob" still attributed the photos to "Alice" because Alice's cookie was active. 
    *   *How I fixed it:* I directed the AI to add a conditional check: `if ($existingUser['normalized_name'] === $normalizedName)`. If the name typed doesn't match the cookie owner, it forces the creation of a brand-new user profile and issues a fresh cookie.
6.  **Prompt:** "Act as a Senior UI/UX Designer. Generate an external CSS file using Glassmorphism for the TV display and a clean, modern card layout for the mobile upload form."
    *   *Result:* The AI generated the `style.css` file which unified both views.