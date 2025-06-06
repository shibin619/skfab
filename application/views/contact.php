<?php include 'layout/header.php'; ?>

    <!-- Page Header Start -->
    <div class="container-fluid page-header">
        <h1 class="display-3 text-uppercase text-white mb-3">Contact</h1>
        <div class="d-inline-flex text-white">
            <h6 class="text-uppercase m-0"><a href="" class="text_skfab_blue">Home</a></h6>
            <h6 class="text-white m-0 px-3">/</h6>
            <h6 class="text-uppercase text-white m-0">Contact</h6>
        </div>
    </div>
    <!-- Page Header Start -->
    <?php if($this->session->flashdata('success')): ?>
        <div class="alert alert-success"><?= $this->session->flashdata('success'); ?></div>
    <?php endif; ?>
    <?php if($this->session->flashdata('error')): ?>
        <div class="alert alert-danger"><?= $this->session->flashdata('error'); ?></div>
    <?php endif; ?>
    <!-- Contact Start -->
    <div class="container-fluid py-6 px-5">
        <div class="text-center mx-auto mb-5" style="max-width: 600px;">
            <h1 class="display-5 text-uppercase mb-4">Please <span class="text_skfab_blue">Feel Free</span> To Contact Us</h1>
        </div>
        <div class="row gx-0 align-items-center">
            <div class="col-lg-6 mb-5 mb-lg-0" style="height: 600px;">
            <iframe class="w-100 h-100"
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3914.1973290061925!2d77.9783293!3d11.7070437!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3babfd217d3378a3%3A0x6d9aa0670d8c05d6!2sSK%20Fabrication%20Construction%20%26%20Promoters!5e0!3m2!1sen!2sin!4v1716457435623!5m2!1sen!2sin"
                frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0">
            </iframe>
            </div>
            <div class="col-lg-6">
                <form action="<?= BASE_URL?>send_message" method="post">
                    <div class="contact-form bg-light p-5">
                        <div class="row g-3">
                            <div class="col-12 col-sm-6">
                                <input type="text" name="name" class="form-control border-0" placeholder="Your Name" style="height: 55px;" required>
                            </div>
                            <div class="col-12 col-sm-6">
                                <input type="email" name="email" class="form-control border-0" placeholder="Your Email" style="height: 55px;" required>
                            </div>
                            <div class="col-12">
                                <input type="text" name="subject" class="form-control border-0" placeholder="Subject" style="height: 55px;" required>
                            </div>
                            <div class="col-12">
                                <textarea name="message" class="form-control border-0" rows="4" placeholder="Message" required></textarea>
                            </div>
                            <div class="col-12">
                                <button class="btn btn_skfab_blue w-100 py-3" type="submit">Send Message</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div> 
        </div>
    </div>
    <!-- Contact End -->

    <?php include 'layout/footer.php'; ?>