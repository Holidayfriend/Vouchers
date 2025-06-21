<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Single Page Layout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">


</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">MyLogo</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="#about">About Us</a></li>
                    <li class="nav-item"><a class="nav-link" href="#contact">Contact Us</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Image Section -->
    <section class="text-center my-4">

    </section>

    <!-- Contact Section -->
    <section id="contact" class="container text-center my-4">
        <h2>Contact Us</h2>
        <p>Email: contact@example.com | Phone: +123 456 7890</p>
    </section>

    <!-- Content Divs -->
    <section class="container my-4">
        <div class="row">
            <div class="col-md-4 p-3 bg-light">Content Box 1</div>
            <div class="col-md-4 p-3 bg-secondary text-white">Content Box 2</div>
            <div class="col-md-4 p-3 bg-light">Content Box 3</div>
        </div>
    </section>

   

    <iframe src="http://localhost/vouchers/api/voucher_widget.php?define=Q2eNJi930HBXZQ&lang=en" width="100%" height="600px"
        style="border:none;">

    </iframe>


  
    <footer class="bg-dark text-white text-center p-3 mt-4">
        &copy; 2024 My Website. All Rights Reserved.
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


</body>

</html>