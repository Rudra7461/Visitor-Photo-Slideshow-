Because this application relies heavily on file system manipulation and client-side asynchronous DOM updates, a structured manual test plan was executed.

## Environment Setup
*   OS: Windows 11
*   Server: XAMPP (Apache + MySQL)
*   Browsers Tested: Chrome Desktop (TV View), Chrome Mobile Emulation (Upload View)

## Test Cases Executed

### Test 1: File Size & Type Validation (Security Focus)
*   **Action:** Attempted to upload a 10MB PDF file and a `.txt` file renamed to `.jpg`.
*   **Expected Result:** The system should reject both files and not save them to the `uploads/` directory.
*   **Actual Result:** Both files were successfully rejected by the `finfo_file` MIME check and the 5MB size limit check in `upload.php`.
*   **Status:** PASSED ✅

### Test 2: Privacy Consent Enforcement
*   **Action:** Filled out the upload form completely, but intentionally bypassed/removed the HTML `required` attribute on the consent checkbox using Chrome DevTools, and submitted.
*   **Expected Result:** The PHP backend must block the upload.
*   **Actual Result:** The backend caught the missing `$_POST['consent']` flag and halted execution with "Consent required". No database entries were made.
*   **Status:** PASSED ✅

### Test 3: Returning Visitor Recognition
*   **Action:** Uploaded a photo as "Alex". Closed the tab, reopened `index.php`, and uploaded a second photo as "Alex".
*   **Expected Result:** The system recognizes the cookie, updates the `visit_count`, and the slideshow displays the "Returning Visitor" badge.
*   **Actual Result:** The TV display correctly showed the badge, and *both* the old and new photos cycled in the 3-second loop.
*   **Status:** PASSED ✅

### Test 4: Same-Device, Different User Isolation
*   **Action:** Immediately after Alex uploaded, changed the name input on the same browser to "Bob" and uploaded a photo.
*   **Expected Result:** The system must ignore Alex's cookie, create a brand-new user profile for Bob, and not show Bob as a returning visitor.
*   **Actual Result:** Bob was correctly logged as a new user with a `visit_count` of 1, and the photos were successfully separated in the database.
*   **Status:** PASSED ✅