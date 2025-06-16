document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    
    // Simple search functionality
    // searchInput.addEventListener('input', function(e) {
    //     const searchQuery = e.target.value.toLowerCase();
    //     console.log('Searching for:', searchQuery);
    //     // Here you would typically make an AJAX call to your backend
    //     // or filter through your product list
    //     // For demonstration, we'll just log the search query
    // });

    // Add click handlers for category buttons
    // const categoryButtons = document.querySelectorAll('.category-btn');
    // categoryButtons.forEach(button => {
    //     button.addEventListener('click', function() {
    //         const category = this.querySelector('span').textContent.toLowerCase();
    //         // Navigate to the category page
    //         window.location.href = `${category}.html`;
    //     });
    // });

    // Add click handlers for featured cards
    const featuredCards = document.querySelectorAll('.featured-card');
    featuredCards.forEach(card => {
        card.addEventListener('click', function() {
            const title = this.querySelector('h3').textContent.toLowerCase().replace(/\s+/g, '-');
            // Navigate to the featured section page
            window.location.href = `${title}.html`;
        });
    });

    // Add click handler for cart button
    const cartButton = document.querySelector('.btn-primary');
    if (cartButton) {
        cartButton.addEventListener('click', function() {
            window.location.href = 'rentCar.php';
        });
    }

    const prodsButton = document.querySelector('.btn-secondary');
    if (prodsButton) {
        // print('Products button found!');
        prodsButton.addEventListener('click', function() {
            window.location.href = 'show.php';
        });
    }

    // if (cartButton) {
    //     cartButton.addEventListener('click', function() {
    //         window.location.href = 'cart.html';
    //     });
    // }

    // Add click handler for home button
    const homeButton = document.querySelector('.button-heading');
    if (homeButton) {
        homeButton.addEventListener('click', function() {
            window.location.href = 'Untitled-1.html';
        });
    }
});