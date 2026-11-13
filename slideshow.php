<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Slideshow Display</title>
    <!-- Link to external stylesheet -->
    <link rel="stylesheet" href="style.css">
</head>

<body class="tv-theme">
    
    <!-- This container MUST exist for the JavaScript to inject the photos into -->
    <div id="slideshow-container">
        <div class="empty-state">Waiting for photos to be uploaded...</div>
    </div>

    <script>
        let photos = [];
        let currentIndex = 0;

        async function fetchPhotos() {
            try {
                const res = await fetch('api_get_photos.php');
                const data = await res.json();
                
                if (data.photos && data.photos.length > 0) {
                    const wasEmpty = photos.length === 0;
                    photos = data.photos;
                    if (wasEmpty) showNextSlide();
                }
            } catch (err) {
                console.error("Failed to fetch photos:", err);
            }
        }

        function showNextSlide() {
            if (photos.length === 0) return;
            
            const photo = photos[currentIndex];
            const isReturning = photo.visit_count > 1 
                ? '<span class="badge">Returning Visitor</span>' 
                : '';
            
            // Notice this now correctly targets the 'slideshow-container' ID
            document.getElementById('slideshow-container').innerHTML = `
                <img src="uploads/${photo.stored_filename}" class="photo-slide" alt="Event Photo">
                <div class="info-card">
                    <h2 class="visitor-name">${photo.name} ${isReturning}</h2>
                </div>
            `;
            
            currentIndex = (currentIndex + 1) % photos.length;
        }

        // Initial fetch
        fetchPhotos();
        
        // Poll for new photos every 2 seconds
        setInterval(fetchPhotos, 2000); 
        
        // Change slide every 3 seconds (3000ms)
        setInterval(showNextSlide, 3000);
    </script>
</body>
</html>