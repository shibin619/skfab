    <?php 
    include 'layout/header.php'; ?>


        <!-- Carousel Start -->
        <div class="container-fluid p-0">
            <div id="header-carousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img class="w-100" src="<?=ASSETS_PATH?>img/carousel-1.jpg" alt="Image">
                        <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                            <div class="p-3" style="max-width: 900px;">
                                <i class="fa fa-home fa-4x text_skfab_blue mb-4 d-none d-sm-block"></i>
                                <h1 class="display-2 text-uppercase text-white mb-md-4">Build Your Dream House With Us</h1>
                                <a href="" class="btn btn_skfab_blue py-md-3 px-md-5 mt-2">Get A Quote</a>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img class="w-100" src="<?=ASSETS_PATH?>img/carousel-2.jpg" alt="Image">
                        <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                            <div class="p-3" style="max-width: 900px;">
                                <i class="fa fa-tools fa-4x text_skfab_blue mb-4 d-none d-sm-block"></i>
                                <h1 class="display-2 text-uppercase text-white mb-md-4">We Are Trusted For Your Project</h1>
                                <a href="" class="btn btn_skfab_blue py-md-3 px-md-5 mt-2">Contact Us</a>
                            </div>
                        </div>
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#header-carousel"
                    data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#header-carousel"
                    data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>
        <!-- Carousel End -->

        <?php  require('layout/about_layout.php');?>
        <?php  require('layout/service_layout.php');?>
 

        <!-- Appointment Start -->
        <!-- <div class="container-fluid py-6 px-5">
            <div class="row gx-5">
                <div class="col-lg-4 mb-5 mb-lg-0">
                    <div class="mb-4">
                        <h1 class="display-5 text-uppercase mb-4">Request A <span class="text_skfab_blue">Call Back</span></h1>
                    </div>
                    <p class="mb-5">Looking to discuss your fabrication or construction project? Our team of experts is here to assist you with tailored solutions and top-notch service. Simply fill out the form, and we’ll get back to you promptly to address your needs. Whether it’s custom fabrication, structural design, or turnkey construction, we’re just a call away. Let’s build something extraordinary together!.</p>
                    <a class="btn btn_skfab_blue py-3 px-5" href="">Get A Quote</a>
                </div>
                <div class="col-lg-8">
                    <div class="bg-light text-center p-5">
                        <form>
                            <div class="row g-3">
                                <div class="col-12 col-sm-6">
                                    <input type="text" class="form-control border-0" placeholder="Your Name" style="height: 55px;">
                                </div>
                                <div class="col-12 col-sm-6">
                                    <input type="email" class="form-control border-0" placeholder="Your Email" style="height: 55px;">
                                </div>
                                <div class="col-12 col-sm-6">
                                    <div class="date" id="date" data-target-input="nearest">
                                        <input type="text"
                                            class="form-control border-0 datetimepicker-input"
                                            placeholder="Call Back Date" data-target="#date" data-toggle="datetimepicker" style="height: 55px;">
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <div class="time" id="time" data-target-input="nearest">
                                        <input type="text"
                                            class="form-control border-0 datetimepicker-input"
                                            placeholder="Call Back Time" data-target="#time" data-toggle="datetimepicker" style="height: 55px;">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <textarea class="form-control border-0" rows="5" placeholder="Message"></textarea>
                                </div>
                                <div class="col-12">
                                    <button class="btn btn_skfab_blue w-100 py-3" type="submit">Submit Request</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div> -->
        <!-- Appointment End -->


        <!-- Portfolio Start -->
        <div class="container-fluid bg-light py-6 px-5">
            <div class="text-center mx-auto mb-5" style="max-width: 600px;">
                <h1 class="display-5 text-uppercase mb-4">Some Of Our <span class="text_skfab_blue">Popular</span> Dream Projects</h1>
            </div>
            <div class="row gx-5">
                <div class="col-12 text-center">
                    <div class="d-inline-block bg-dark-radial text-center pt-4 px-5 mb-5">
                        <ul class="list-inline mb-0" id="portfolio-flters">
                            <li class="btn btn-outline-primary bg-white p-2 active mx-2 mb-4" data-filter="*">
                                <img src="<?=ASSETS_PATH?>img/portfolio-1.jpg" style="width: 150px; height: 100px;">
                                <div class="position-absolute top-0 start-0 end-0 bottom-0 m-2 d-flex align-items-center justify-content-center" style="background: rgba(4, 15, 40, .3);">
                                    <h6 class="text-white text-uppercase m-0">All</h6>
                                </div>
                            </li>
                            <li class="btn btn-outline-primary bg-white p-2 mx-2 mb-4" data-filter=".first">
                                <img src="<?=ASSETS_PATH?>img/portfolio-2.jpg" style="width: 150px; height: 100px;">
                                <div class="position-absolute top-0 start-0 end-0 bottom-0 m-2 d-flex align-items-center justify-content-center" style="background: rgba(4, 15, 40, .3);">
                                    <h6 class="text-white text-uppercase m-0">Construction</h6>
                                </div>
                            </li>
                            <li class="btn btn-outline-primary bg-white p-2 mx-2 mb-4" data-filter=".second">
                                <img src="<?=ASSETS_PATH?>img/portfolio-3.jpg" style="width: 150px; height: 100px;">
                                <div class="position-absolute top-0 start-0 end-0 bottom-0 m-2 d-flex align-items-center justify-content-center" style="background: rgba(4, 15, 40, .3);">
                                    <h6 class="text-white text-uppercase m-0">Renovation</h6>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="row g-5 portfolio-container">
                <div class="col-xl-4 col-lg-6 col-md-6 portfolio-item first">
                    <div class="position-relative portfolio-box">
                        <img class="img-fluid w-100" src="<?=ASSETS_PATH?>img/portfolio-1.jpg" alt="">
                        <a class="portfolio-title shadow-sm" href="">
                            <p class="h4 text-uppercase">Project Name</p>
                            <span class="text-body"><i class="fa fa-map-marker-alt text_skfab_blue me-2"></i>123 Street, New York, USA</span>
                        </a>
                        <a class="portfolio-btn" href="<?=ASSETS_PATH?>img/portfolio-1.jpg" data-lightbox="portfolio">
                            <i class="bi bi-plus text-white"></i>
                        </a>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-6 col-md-6 portfolio-item second">
                    <div class="position-relative portfolio-box">
                        <img class="img-fluid w-100" src="<?=ASSETS_PATH?>img/portfolio-2.jpg" alt="">
                        <a class="portfolio-title shadow-sm" href="">
                            <p class="h4 text-uppercase">Project Name</p>
                            <span class="text-body"><i class="fa fa-map-marker-alt text_skfab_blue me-2"></i>123 Street, New York, USA</span>
                        </a>
                        <a class="portfolio-btn" href="<?=ASSETS_PATH?>img/portfolio-2.jpg" data-lightbox="portfolio">
                            <i class="bi bi-plus text-white"></i>
                        </a>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-6 col-md-6 portfolio-item first">
                    <div class="position-relative portfolio-box">
                        <img class="img-fluid w-100" src="<?=ASSETS_PATH?>img/portfolio-3.jpg" alt="">
                        <a class="portfolio-title shadow-sm" href="">
                            <p class="h4 text-uppercase">Project Name</p>
                            <span class="text-body"><i class="fa fa-map-marker-alt text_skfab_blue me-2"></i>123 Street, New York, USA</span>
                        </a>
                        <a class="portfolio-btn" href="<?=ASSETS_PATH?>img/portfolio-3.jpg" data-lightbox="portfolio">
                            <i class="bi bi-plus text-white"></i>
                        </a>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-6 col-md-6 portfolio-item second">
                    <div class="position-relative portfolio-box">
                        <img class="img-fluid w-100" src="<?=ASSETS_PATH?>img/portfolio-4.jpg" alt="">
                        <a class="portfolio-title shadow-sm" href="">
                            <p class="h4 text-uppercase">Project Name</p>
                            <span class="text-body"><i class="fa fa-map-marker-alt text_skfab_blue me-2"></i>123 Street, New York, USA</span>
                        </a>
                        <a class="portfolio-btn" href="<?=ASSETS_PATH?>img/portfolio-4.jpg" data-lightbox="portfolio">
                            <i class="bi bi-plus text-white"></i>
                        </a>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-6 col-md-6 portfolio-item first">
                    <div class="position-relative portfolio-box">
                        <img class="img-fluid w-100" src="<?=ASSETS_PATH?>img/portfolio-5.jpg" alt="">
                        <a class="portfolio-title shadow-sm" href="">
                            <p class="h4 text-uppercase">Project Name</p>
                            <span class="text-body"><i class="fa fa-map-marker-alt text_skfab_blue me-2"></i>123 Street, New York, USA</span>
                        </a>
                        <a class="portfolio-btn" href="<?=ASSETS_PATH?>img/portfolio-5.jpg" data-lightbox="portfolio">
                            <i class="bi bi-plus text-white"></i>
                        </a>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-6 col-md-6 portfolio-item second">
                    <div class="position-relative portfolio-box">
                        <img class="img-fluid w-100" src="<?=ASSETS_PATH?>img/portfolio-6.jpg" alt="">
                        <a class="portfolio-title shadow-sm" href="">
                            <p class="h4 text-uppercase">Project Name</p>
                            <span class="text-body"><i class="fa fa-map-marker-alt text_skfab_blue me-2"></i>123 Street, New York, USA</span>
                        </a>
                        <a class="portfolio-btn" href="<?=ASSETS_PATH?>img/portfolio-6.jpg" data-lightbox="portfolio">
                            <i class="bi bi-plus text-white"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Portfolio End -->


        <!-- Team Start -->
        <div class="container-fluid py-6 px-5">
            <div class="text-center mx-auto mb-5" style="max-width: 600px;">
                <h1 class="display-5 text-uppercase mb-4">We Are <span class="text_skfab_blue">Professional & Expert</span> Workers</h1>
            </div>
            <div class="row g-5">
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="row g-0">
                        <div class="col-10" style="min-height: 300px;">
                            <div class="position-relative h-100">
                                <img class="position-absolute w-100 h-100" src="<?=ASSETS_PATH?>img/team-1.jpg" style="object-fit: cover;">
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="h-100 d-flex flex-column align-items-center justify-content-between bg-light">
                                <a class="btn" href="#"><i class="fab fa-twitter"></i></a>
                                <a class="btn" href="#"><i class="fab fa-facebook-f"></i></a>
                                <a class="btn" href="#"><i class="fab fa-linkedin-in"></i></a>
                                <a class="btn" href="#"><i class="fab fa-instagram"></i></a>
                                <a class="btn" href="#"><i class="fab fa-youtube"></i></a>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="bg-light p-4">
                                <h4 class="text-uppercase">Adam Phillips</h4>
                                <span>CEO & Founder</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="row g-0">
                        <div class="col-10" style="min-height: 300px;">
                            <div class="position-relative h-100">
                                <img class="position-absolute w-100 h-100" src="<?=ASSETS_PATH?>img/team-2.jpg" style="object-fit: cover;">
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="h-100 d-flex flex-column align-items-center justify-content-between bg-light">
                                <a class="btn" href="#"><i class="fab fa-twitter"></i></a>
                                <a class="btn" href="#"><i class="fab fa-facebook-f"></i></a>
                                <a class="btn" href="#"><i class="fab fa-linkedin-in"></i></a>
                                <a class="btn" href="#"><i class="fab fa-instagram"></i></a>
                                <a class="btn" href="#"><i class="fab fa-youtube"></i></a>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="bg-light p-4">
                                <h4 class="text-uppercase">Dylan Adams</h4>
                                <span>Civil Engineer</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="row g-0">
                        <div class="col-10" style="min-height: 300px;">
                            <div class="position-relative h-100">
                                <img class="position-absolute w-100 h-100" src="<?=ASSETS_PATH?>img/team-3.jpg" style="object-fit: cover;">
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="h-100 d-flex flex-column align-items-center justify-content-between bg-light">
                                <a class="btn" href="#"><i class="fab fa-twitter"></i></a>
                                <a class="btn" href="#"><i class="fab fa-facebook-f"></i></a>
                                <a class="btn" href="#"><i class="fab fa-linkedin-in"></i></a>
                                <a class="btn" href="#"><i class="fab fa-instagram"></i></a>
                                <a class="btn" href="#"><i class="fab fa-youtube"></i></a>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="bg-light p-4">
                                <h4 class="text-uppercase">Jhon Doe</h4>
                                <span>Interior Designer</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="row g-0">
                        <div class="col-10" style="min-height: 300px;">
                            <div class="position-relative h-100">
                                <img class="position-absolute w-100 h-100" src="<?=ASSETS_PATH?>img/team-4.jpg" style="object-fit: cover;">
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="h-100 d-flex flex-column align-items-center justify-content-between bg-light">
                                <a class="btn" href="#"><i class="fab fa-twitter"></i></a>
                                <a class="btn" href="#"><i class="fab fa-facebook-f"></i></a>
                                <a class="btn" href="#"><i class="fab fa-linkedin-in"></i></a>
                                <a class="btn" href="#"><i class="fab fa-instagram"></i></a>
                                <a class="btn" href="#"><i class="fab fa-youtube"></i></a>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="bg-light p-4">
                                <h4 class="text-uppercase">Josh Dunn</h4>
                                <span>Painter</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Team End -->


        <!-- Testimonial Start -->
        <div class="container-fluid bg-light py-6 px-5">
            <div class="text-center mx-auto mb-5" style="max-width: 600px;">
                <h1 class="display-5 text-uppercase mb-4">What Our <span class="text_skfab_blue">Happy Cleints</span> Say!!!</h1>
            </div>
            <div class="row gx-0 align-items-center">
                <div class="col-xl-4 col-lg-5 d-none d-lg-block">
                    <img class="img-fluid w-100 h-100" src="<?=ASSETS_PATH?>img/testimonial.jpg">
                </div>
                <div class="col-xl-8 col-lg-7 col-md-12">
                    <div class="testimonial bg-light">
                        <div class="owl-carousel testimonial-carousel">
                            <div class="row gx-4 align-items-center">
                                <div class="col-xl-4 col-lg-5 col-md-5">
                                    <img class="img-fluid w-100 h-100 bg-light p-lg-3 mb-4 mb-md-0" src="<?=ASSETS_PATH?>img/testimonial-1.jpg" alt="">
                                </div>
                                <div class="col-xl-8 col-lg-7 col-md-7">
                                    <h4 class="text-uppercase mb-0">Client Name</h4>
                                    <p>Profession</p>
                                    <p class="fs-5 mb-0"><i class="fa fa-2x fa-quote-left text_skfab_blue me-2"></i> Dolores sed duo
                                        clita tempor justo dolor et stet lorem kasd labore dolore lorem ipsum. At lorem
                                        lorem magna ut labore et tempor diam tempor erat. Erat dolor rebum sit
                                        ipsum.</p>
                                </div>
                            </div>
                            <div class="row gx-4 align-items-center">
                                <div class="col-xl-4 col-lg-5 col-md-5">
                                    <img class="img-fluid w-100 h-100 bg-light p-lg-3 mb-4 mb-md-0" src="<?=ASSETS_PATH?>img/testimonial-2.jpg" alt="">
                                </div>
                                <div class="col-xl-8 col-lg-7 col-md-7">
                                    <h4 class="text-uppercase mb-0">Client Name</h4>
                                    <p>Profession</p>
                                    <p class="fs-5 mb-0"><i class="fa fa-2x fa-quote-left text_skfab_blue me-2"></i> Dolores sed duo
                                        clita tempor justo dolor et stet lorem kasd labore dolore lorem ipsum. At lorem
                                        lorem magna ut labore et tempor diam tempor erat. Erat dolor rebum sit
                                        ipsum.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Testimonial End -->


        <!-- Blog Start -->
        <!-- <div class="container-fluid py-6 px-5">
            <div class="text-center mx-auto mb-5" style="max-width: 600px;">
                <h1 class="display-5 text-uppercase mb-4">Latest <span class="text_skfab_blue">Articles</span> From Our Blog Post</h1>
            </div>
            <div class="row g-5">
                <div class="col-lg-4 col-md-6">
                    <div class="bg-light">
                        <img class="img-fluid" src="<?=ASSETS_PATH?>img/blog-1.jpg" alt="">
                        <div class="p-4">
                            <div class="d-flex justify-content-between mb-4">
                                <div class="d-flex align-items-center">
                                    <img class="rounded-circle me-2" src="<?=ASSETS_PATH?>img/user.jpg" width="35" height="35" alt="">
                                    <span>John Doe</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="ms-3"><i class="far fa-calendar-alt text_skfab_blue me-2"></i>01 Jan, 2045</span>
                                </div>
                            </div>
                            <h4 class="text-uppercase mb-3">Rebum diam clita lorem erat magna est erat</h4>
                            <a class="text-uppercase fw-bold text_skfab_blue" href="">Read More <i class="bi bi-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="bg-light">
                        <img class="img-fluid" src="<?=ASSETS_PATH?>img/blog-2.jpg" alt="">
                        <div class="p-4">
                            <div class="d-flex justify-content-between mb-4">
                                <div class="d-flex align-items-center">
                                    <img class="rounded-circle me-2" src="<?=ASSETS_PATH?>img/user.jpg" width="35" height="35" alt="">
                                    <span>John Doe</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="ms-3"><i class="far fa-calendar-alt text_skfab_blue me-2"></i>01 Jan, 2045</span>
                                </div>
                            </div>
                            <h4 class="text-uppercase mb-3">Rebum diam clita lorem erat magna est erat</h4>
                            <a class="text-uppercase fw-bold text_skfab_blue" href="">Read More <i class="bi bi-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="bg-light">
                        <img class="img-fluid" src="<?=ASSETS_PATH?>img/blog-3.jpg" alt="">
                        <div class="p-4">
                            <div class="d-flex justify-content-between mb-4">
                                <div class="d-flex align-items-center">
                                    <img class="rounded-circle me-2" src="<?=ASSETS_PATH?>img/user.jpg" width="35" height="35" alt="">
                                    <span>John Doe</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="ms-3"><i class="far fa-calendar-alt text_skfab_blue me-2"></i>01 Jan, 2045</span>
                                </div>
                            </div>
                            <h4 class="text-uppercase mb-3">Rebum diam clita lorem erat magna est erat</h4>
                            <a class="text-uppercase fw-bold text_skfab_blue" href="">Read More <i class="bi bi-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->
        <!-- Blog End -->
        
        <?php include 'layout/footer.php'; ?>

