// Get the search button, input, and all cards
const searchButton = document.getElementById('search-button');
const searchInput = document.getElementById('search-input');
const galleryCards = document.querySelectorAll('.card');

// Search function
function searchActivities() {
    const query = searchInput.value.toLowerCase().trim();

    // Loop through each gallery card
    galleryCards.forEach(card => {
        const activityName = card.querySelector('.card-title').textContent.toLowerCase();
        const activityDescription = card.querySelector('.card-text').textContent.toLowerCase();

        // Check if the title or description contains the search query
        if (activityName.includes(query) || activityDescription.includes(query)) {
            card.parentElement.style.display = ''; // Show card if match
        } else {
            card.parentElement.style.display = 'none'; // Hide card if no match
        }
    });
}

// Add event listener to search button
searchButton.addEventListener('click', searchActivities);

// Add event listener for Enter key in search input
searchInput.addEventListener('keypress', function (e) {
    if (e.key === 'Enter') {
        searchActivities();
    }
});
