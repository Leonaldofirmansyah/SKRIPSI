<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CV.SURYA TEKNIK UTAMA</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Navbar */
        .navbar-custom {
            background-color: black; /* Dark background color for navbar */
            position: sticky;
            top: 0; /* Stick the navbar to the top */
            z-index: 1000; /* Ensure it stays on top of other content */
            width: 100%; /* Full width */
        }
        .navbar-custom .navbar-brand,
        .navbar-custom .nav-link {
            color: #ffffff;
        }
        .navbar-custom .nav-link:hover {
            color: #d1d1d1;
        }
        .navbar-custom .navbar-brand {
            display: flex;
            align-items: center;
        }
        .navbar-brand img {
            max-height: 50px; /* Maximum height of the logo in navbar */
            width: auto; /* Maintain aspect ratio */
            margin-right: 10px;
        }
        /* Navbar Toggler */
        .navbar-custom .navbar-toggler {
            border-color: rgba(255, 255, 255, 0.1); /* Toggler border color */
        }
        .navbar-custom .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3E%3Cpath stroke='rgba(255, 255, 255, 0.5)' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3E%3C/svg%3E");
        }
        /* Logo */
        .profile {
            position: relative;
            padding: 0px 0;
            background-color: #f8f9fa; /* Light background color for better contrast */
        }
        .profile .profile-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            color: #ffffff;
            z-index: 10; /* Make sure text is above the image */
            max-width: 90%; /* Limit width to 90% of the container */
        }
        .profile .profile-content h1 {
            font-size: 5.5rem;
            margin-bottom: 20px;
        }
        .profile .profile-content p {
            font-size: 1.2rem;
        }
        .logo {
            width: 100%;
            height: auto;
            max-height: 100vh; /* Max height of the logo */
            object-fit: cover; /* Cover the area without distorting the aspect ratio */
        }
/* Admin Section */
#admin {
    background-color: #f8f9fa;
    padding: 60px 0;
    min-height: 100vh; /* Full viewport height */
}

#admin h2 {
    margin-bottom: 40px;
    font-size: 2.5rem;
    color: #343a40; /* Dark text color for better contrast */
}

.services-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 100px; /* Spacing between grid items */
}

.one_third {
    background-color: #ffffff;
    padding: 20px;
}

.one_third h3 {
    font-size: 30px;
    margin: 0;
    color: #007bff; /* Primary color for text */
}

@media (max-width: 768px) {
    #admin h2 {
        font-size: 2rem;
    }
    .one_third h3 {
        font-size: 1rem;
    }
}

        @media (max-width: 768px) {
            .profile .profile-content h1 {
                font-size: 1.5rem;
            }
            .profile .profile-content p {
                font-size: 1rem;
            }
        }
        /* Gaya khusus untuk section produk */
        #products {
            background-color: grey;
            padding: 60px 0;
        }
        #products h2 {
            color: red;
            margin-bottom: 40px;
        }
        #products .card {
            border: none;
            background-color: whitesmoke;
            /*box-shadow: rgba(14, 30, 37, 0.12) 0px 2px 4px 0px, rgba(14, 30, 37, 0.32) 0px 2px 16px 0px;*/
            box-shadow: rgba(6, 24, 44, 0.4) 0px 0px 0px 2px, rgba(6, 24, 44, 0.65) 0px 4px 6px -1px, rgba(255, 255, 255, 0.08) 0px 1px 0px inset;
        }
        #products .card-img-top {
            width: 100%; /* Atur lebar gambar agar sesuai dengan lebar kartu */
            height: 200px; /* Tetapkan tinggi tetap untuk gambar */
            object-fit: cover; /* Jaga rasio aspek gambar dan potong jika perlu */
        }
        /* Section backgrounds */
        #home {
            background: url('images/homebg.jpg') no-repeat center center fixed;
            background-size: cover;
        }
        #admin {
            background-color: #f8f9fa;
            padding: 60px 0;
        }
        #contact {
            background-color: #e9ecef;
            padding: 60px 0;
        }
        #about {
            background-color: #dee2e6;
            padding: 60px 0;
        }
    </style>
</head>
<body>

<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-custom">
    <a class="navbar-brand" href="#">
        <img src="images/logo.png" alt="Company Logo">
        CV.Surya Teknik Utama
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="#home">HOME</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#contact">KONTAK</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#products">PRODUK</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#about">TENTANG</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="login.php">MASUK</a>
            </li>
        </ul>
    </div>
</nav>

<!-- Profile Section -->
<section id="home" class="profile text-center">
    <img src="images/homebg.jpg" alt="Company Logo" class="logo">
    <div class="profile-content">
        <h1 class="title">CV.SURYA TEKNIK UTAMA</h1>
        <p> SPECIALIST POLYURETHANE & RUBBER </p>
    </div>
</section>

<style>
    .profile-content {
        text-align: center;
    }
    .title {
        display: inline-block;
        width: 100%;
    }
</style>
<!-- Produk Section -->
<section id="products" class="text-center py-5">
    <div class="container">
        <h2>Produk Kami</h2>
        <div class="row">
            <!-- Produk 1 -->
            <div class="col-md-4">
                <div class="card mb-4">
                    <img src="images/p1.jpg" class="card-img-top" alt="Product 1">
                    <div class="card-body">
                        <h5 class="card-title">Rubber Sheet</h5>
                        <p class="card-text">karet lembaran yang terbuat dari karet murni berkualitas tanpa ada lapisan plat bahan dan mempunyai berbagai macam fungsi</p>
                        <a href="login.php" class="btn btn-primary">Pesan</a>
                    </div>
                </div>
            </div>
            <!-- Produk 2 -->
            <div class="col-md-4">
                <div class="card mb-4">
                    <img src="images/p2.jpg" class="card-img-top" alt="Product 2">
                    <div class="card-body">
                        <h5 class="card-title">Rubber Roll</h5>
                        <p class="card-text">karet lembaran yang terbuat dari karet murni berkualitas tanpa ada lapisan plat bahan dan mempunyai berbagai macam fungsi</p>
                        <a href="login.php" class="btn btn-primary">Pesan</a>
                    </div>
                </div>
            </div>
            <!-- Produk 3 -->
            <div class="col-md-4">
                <div class="card mb-4">
                    <img src="images/p3.jpg" class="card-img-top" alt="Product 3">
                    <div class="card-body">
                        <h5 class="card-title">Rubber O-Ring</h5>
                        <p class="card-text">karet lembaran yang terbuat dari karet murni berkualitas tanpa ada lapisan plat bahan dan mempunyai berbagai macam fungsi</p>
                        <a href="login.php" class="btn btn-primary">Pesan</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Admin Section -->
<section id="admin" class="text-center">
    <div class="container">
        <h2>Layanan</h2>
        <div class="services-grid">
            <div class="one_third">
                <h3>RUBBER ACCESORIES</h3>
            </div>
            <div class="one_third">
                <h3>RUBBER MEMBRAN</h3>
            </div>
            <div class="one_third">
                <h3>ROLLER RUBBER</h3>
            </div>
            <div class="one_third">
                <h3>O-RING SILICON</h3>
            </div>
            <div class="one_third">
                <h3>AUTOMOTIVE PART</h3>
            </div>
            <div class="one_third">
                <h3>PACKING VITON</h3>
            </div>
            <div class="one_third">
                <h3>INDUSTRIAL PART</h3>
            </div>
            <div class="one_third">
                <h3>TEXTILE PART</h3>
            </div>
            <div class="one_third">
                <h3>HARDCROME</h3>
            </div>
            <div class="one_third">
                <h3>SEAL</h3>
            </div>
        </div>
    </div>
</section>


<!-- Contact Section -->
<section id="contact" class="text-center">
    <div class="container">
        <h2>Contact</h2>
        <p>Contact section content goes here. Menurut Kamus Besar Bahasa Indonesia (KBBI), sistem adalah perangkat unsur yang secara teratur saling berkaitan sehingga membentuk suatu totalitas. Sistem juga diartikan sebagai susunan yang teratur dari pandangan, teori, asas, dan sebagainya.</p>
    </div>
</section>

<!-- About Section -->
<section id="about" class="text-center">
    <div class="container">
        <h2>Tentang Kami</h2>
        <p>T
CV. Surya Teknik Utama

Perusahaan yang bergerak khusus memproduksi spare part yang terbuat dari polyurethane, rubber, silicone dan plastik seperti suku cadang / spare part escalator, elevator, industry keramik, industry kayu, industry textile, industry pertambangan dll. Perusahaan terus menambah lini produksi dibidang machining dan pisau industry. Dengan perkembangan teknologi yang demikian pesatnya merupakan tantangan kami untuk berkarya, semuanya ini dimungkinkan dengan adanya kemampuan dan keterampilan para tenaga ahli dan staff yang selalu siap menanggulangi segala tantangan dan kesulitan dibidangnya. Perusahaan kami mampu bersaing dan siap memberi pelayanan terbaik kepada para customernya.
</p>
    </div>
</section>


<!-- Bootstrap and jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
