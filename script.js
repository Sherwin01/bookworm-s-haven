// Update the year in the footer dynamically
document.getElementById('year').textContent = new Date().getFullYear();

// Scroll to Top Button functionality
const scrollToTopBtn = document.createElement('button');
scrollToTopBtn.textContent = '↑';
scrollToTopBtn.classList.add('scroll-to-top-btn');
document.body.appendChild(scrollToTopBtn);

// Show the scroll-to-top button when scrolling down
window.addEventListener('scroll', function () {
    if (window.scrollY > 300) {
        scrollToTopBtn.style.display = 'block';
    } else {
        scrollToTopBtn.style.display = 'none';
    }
});

// Smooth scroll to top
scrollToTopBtn.addEventListener('click', function () {
    window.scrollTo({ top: 0, behavior: 'smooth' });
});

// Initialize the Bootstrap carousel to autoplay
const myCarousel = new bootstrap.Carousel(document.querySelector('#bestProductsCarousel'), {
    interval: 3000,  // Adjust the autoplay speed (milliseconds)
    ride: 'carousel' // Enable auto play when page loads
});

// Get the modal
const modal = document.getElementById("myModal");

// Get the button that opens the modal
const ctaButton = document.getElementById("ctaButton");

// Get the <span> element that closes the modal
const closeButton = document.getElementById("closeButton");

// When the user clicks the button, open the modal
ctaButton.onclick = function() {
  modal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
closeButton.onclick = function() {
  modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target === modal) {
    modal.style.display = "none";
  }
}

// For search bar

document.getElementById("searchForm").addEventListener("submit", function (e) {
    const query = document.getElementById("searchInput").value.trim();
    if (query === "") {
        alert("Please enter a search query.");
        e.preventDefault(); // Prevent form submission
    }
  });
