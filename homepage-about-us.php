<?php

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Bookworm's Haven</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="homepage.php">
            <img src="logo.jpg" alt="Bookstore Logo" style="height: 40px; width: auto;">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="homepage.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="homepage-products.php">Products</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="homepage-about-us">About Us</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="profile.php">My Profile</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- About Us Section -->
<div class="container mt-5">
    <h2 class="text-center mb-4">About Bookworm's Haven</h2>
    <p>Bookworm's Haven is your ultimate destination for discovering and purchasing your next favorite book. Whether you're looking for the latest bestseller or a hidden gem, we have something for every book lover.</p>

    <!-- Meet Our Team Section -->
    <div class="team-section mt-5">
        <h3 class="text-center">Meet Our Team</h3>
        <div class="row">
            <div class="col-md-4 text-center">
                <h4 class="mt-3">Sherwin Lim</h4>
                <p>Founder & CEO</p>
                <p>Passionate about books and creating a platform for readers to discover new favorites.</p>
            </div>
            <div class="col-md-4 text-center">
                <h4 class="mt-3">Jane Doe</h4>
                <p>Marketing Director</p>
                <p>Ensuring that Bookworm's Haven stays connected with the latest trends in the book world.</p>
            </div>
            <div class="col-md-4 text-center">
                <h4 class="mt-3">John Smith</h4>
                <p>Customer Support</p>
                <p>Helping customers with their queries and ensuring smooth shopping experience.</p>
            </div>
        </div>
    </div>

    <!-- Contact Us Section -->
    <div class="contact-us-section mt-5">
        <h3 class="text-center">Contact Us</h3>
        <p class="text-center">We'd love to hear from you! Feel free to reach out for any questions, feedback, or support inquiries.</p>
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <form>
                    <div class="mb-3">
                        <label for="name" class="form-label">Your Name</label>
                        <input type="text" class="form-control" id="name" placeholder="Enter your name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Your Email</label>
                        <input type="email" class="form-control" id="email" placeholder="Enter your email" required>
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label">Your Message</label>
                        <textarea class="form-control" id="message" rows="4" placeholder="Enter your message" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Send Message</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="bg-dark text-white py-4">
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-3">
                <h5>Bookworm's Haven</h5>
                <p>Metro Manila, Philippines</p>
                <p>09063616629</p>
                <p>sherwinlim8899@gmail.com</p>
            </div>

            <div class="col-md-4 mb-3">
                <h5>Support</h5>
                <p><a href="homepage-contact-us.html" class="text-white">Contact Us</a></p>
            </div>

            <div class="col-md-4 mb-3">
                <h5>Company</h5>
                <p><a href="about-us.php" class="text-white">About</a></p>
                <p><a href="admin-login.php" class="text-white">Login</a></p>
            </div>
        </div>

        <div class="text-center">
            <a href="https://www.facebook.com/sherwinliminhoo/" class="text-white me-3"><i class='bx bxl-facebook'></i></a>
            <a href="https://x.com/SherwinLim18" class="text-white me-3"><i class='bx bxl-twitter'></i></a>
            <a href="https://www.instagram.com/sherwinliminhoo/" class="text-white me-3"><i class='bx bxl-instagram'></i></a>
            <a href="https://www.linkedin.com/in/sherwin-lim-5a3520268/" class="text-white me-3"><i class='bx bxl-linkedin'></i></a>
        </div>

        <div class="text-center mt-3">
            <p>&copy; <span id="year"></span> Bookworm's Haven. All Rights Reserved. Designed By Sherwin Lim</p>
        </div>
    </div>
</footer>


<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

<script>
    
    document.getElementById("year").textContent = new Date().getFullYear();
</script>

</body>
</html>
