<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title><?=$data['site_owner']?></title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="<?=$data['site_owner']?>" name="keywords">
    <meta content="<?=$data['site_owner']?>" name="description">

    <!-- Favicon -->
    <link href="<?=$data['fav_url']?>" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Roboto:wght@500;700&display=swap" rel="stylesheet"> 

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="<?=ASSETS_PATH?>lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="<?=ASSETS_PATH?>lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />
    <link href="<?=ASSETS_PATH?>lib/lightbox/css/lightbox.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="<?=ASSETS_PATH?>css/bootstrap.min.css" rel="stylesheet">

    <style>
        .page-header {
            background: linear-gradient(rgba(4, 15, 40, .7), rgba(4, 15, 40, .7)), 
                url('<?= ASSETS_PATH ?>img/carousel-1.jpg') center center no-repeat;
        }
        /* Default styles */
        .heading {
                font-size: 1.5rem; /* Adjust as needed for default size */
                text-align: left; /* Default alignment */
                display: flex;
                align-items: center;
                word-wrap: break-word; /* Enable word wrapping */
                overflow-wrap: break-word; /* Ensure word wrapping works across browsers */
                white-space: normal; /* Allow text to break onto multiple lines */
            }

            .logo_size {
                width: 40px; /* Adjust for a reasonable size */
                height: auto; /* Maintain aspect ratio */
            }

            /* For smaller screens */
            @media (max-width: 768px) {
                .heading {
                    font-size: 1.2rem; /* Smaller font for mobile */
                    text-align: center; /* Center alignment on mobile */
                    flex-direction: column; /* Stack logo and text vertically */
                    word-wrap: break-word; /* Enable word wrapping for mobile */
                    overflow-wrap: break-word; /* Ensure word wrapping works on mobile */
                    white-space: normal; /* Allow text to break onto multiple lines on mobile */
                }

                .logo_size {
                    width: 30px; /* Smaller logo for mobile */
                }
            }

        /*     .heading{
            font-size: 28px;
        }
        .logo_size{
            min-height: 60px;
            width: 60px;
        } */
    </style>


    <!-- Template Stylesheet -->
    <link href="<?=ASSETS_PATH?>css/style.css" rel="stylesheet">
</head>

<body>
    <!-- Topbar Start -->
    <div class="container-fluid px-5 d-none d-lg-block">
        <div class="row gx-5">
            <div class="col-lg-4 text-center py-3">
                <div class="d-inline-flex align-items-center">
                    <i class="bi bi-geo-alt fs-1 text_skfab_blue me-3"></i>
                    <div class="text-start">
                        <h6 class="text-uppercase fw-bold">Our Office</h6>
                        <span><?=$data['site_address']?></span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 text-center border-start border-end py-3">
                <div class="d-inline-flex align-items-center">
                    <i class="bi bi-envelope-open fs-1 text_skfab_blue me-3"></i>
                    <div class="text-start">
                        <h6 class="text-uppercase fw-bold">Email Us</h6>
                        <span><?=$data['site_email']?></span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 text-center py-3">
                <div class="d-inline-flex align-items-center">
                    <i class="bi bi-phone-vibrate fs-1 text_skfab_blue me-3"></i>
                    <div class="text-start">
                        <h6 class="text-uppercase fw-bold">Call Us</h6>
                        <span><?=$data['site_num']?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Topbar End -->


    <!-- Navbar Start -->
    <div class="container-fluid sticky-top bg-dark bg-light-radial shadow-sm px-5 pe-lg-0">
        <nav class="navbar navbar-expand-lg bg-dark bg-light-radial navbar-dark py-3 py-lg-0">
            <a href="<?=BASE_URL?>" class="navbar-brand">
                <h6 class="m-0 display-4 text-uppercase text-white heading"><img src="<?=$data['logo_url']?>" class="logo_size" alt="">&nbsp;<?=$data['site_owner']?></h6>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <div class="navbar-nav ms-auto py-0">
                    <a href="<?=BASE_URL?>" class="nav-item nav-link active">Home</a>
                    <a href="<?=BASE_URL?>about" class="nav-item nav-link">About</a>
                    <a href="<?=BASE_URL?>service" class="nav-item nav-link">Service</a>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Pages</a>
                        <div class="dropdown-menu m-0">
                            <a href="<?=BASE_URL?>project" class="dropdown-item">Our Project</a>
                            <a href="<?=BASE_URL?>team" class="dropdown-item">The Team</a>
                            <a href="<?=BASE_URL?>testimonial" class="dropdown-item">Testimonial</a>
                            <!-- <a href="<?=BASE_URL?>blog" class="dropdown-item">Blog Grid</a>
                            <a href="<?=BASE_URL?>detail" class="dropdown-item">Blog Detail</a> -->
                        </div>
                    </div>
                    <a href="<?=BASE_URL?>contact" class="nav-item nav-link">Contact</a>
                    <a href="" class="nav-item nav-link bg_skfab_blue text-white px-5 ms-3 d-none d-lg-block">Get A Quote <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>
        </nav>
    </div>
    <!-- Navbar End -->