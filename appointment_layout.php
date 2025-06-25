    <!-- Appointment Start -->
    <div class="container-fluid bg-light py-6 px-5">
            <div class="row gx-5">
                <div class="col-lg-4 mb-5 mb-lg-0">
                    <div class="mb-4">
                        <h1 class="display-5 text-uppercase mb-4">Request A <span class="text_skfab_blue">Call Back</span></h1>
                    </div>
                    <p class="mb-5">We value your time and are here to provide quick, personalized support. Reach out now, and let us help bring your project to life.

                    Please provide your details, and our team will get in touch with you promptly. We are committed to answering your questions and assisting you with the best solutions tailored to your needs. Your satisfaction is our priority, and we look forward to connecting with you soon.</p>
                    <a class="btn btn_skfab_blue py-3 px-5" href="">Get A Quote</a>
                </div>
                <div class="col-lg-8">
                    <div class="bg-white text-center p-5">

                        <form action="<?= BASE_URL?>appoinment_for_callback" method="post">
                            <div class="row g-3">
                                <div class="col-12 col-sm-6">
                                    <input type="text" name="name" class="form-control bg-light border-0" placeholder="Your Name" style="height: 55px;" required>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <input type="email" name="email" class="form-control bg-light border-0" placeholder="Your Email" style="height: 55px;" required>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <input type="text" name="callback_date" class="form-control bg-light border-0 datetimepicker-input " placeholder="Call Back Date" style="height: 55px;" data-target="#date" data-toggle="datetimepicker" required>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <input type="text" name="callback_time" class="form-control bg-light border-0 datetimepicker-input" placeholder="Call Back Time" data-target="#time" data-toggle="datetimepicker" style="height: 55px;" required>
                                </div>
                                <div class="col-12">
                                    <textarea name="message" class="form-control bg-light border-0" rows="5" placeholder="Message" required></textarea>
                                </div>
                                <div class="col-12">
                                    <button class="btn btn_skfab_blue w-100 py-3" type="submit">Submit Request</button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
    </div>
    <!-- Appointment End -->