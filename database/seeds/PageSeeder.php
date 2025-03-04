<?php

use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        $homeHTML = '<div class="home-partners">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="partners-content">
                                        <h4>Now accepting orders through our food partners.</h4>
                                        <p>Foodpanda and GrabFood orders are accepted from 10AM to 7PM only.</p>
                                        <div class="food-partners">
                                          <img src="http://localhost:8000/laravel-filemanager/file-manager/Others/fd1.png" /> 
                                          <img src="http://localhost:8000/laravel-filemanager/file-manager/Others/fd2.png" /> 
                                          <img src="http://localhost:8000/laravel-filemanager/file-manager/Others/fd3.png" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                
                    <div class="intro-wrapper">
                        <div class="container">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="intro-content">
                                        <h2>Lydia\'s Lechon Celebrating 55th Years</h2>
                                        <p>Lydia’s Lechon started out as a small stall in Baclaran during the ‘60s serving its famous original boneless lechon stuffed with seafood paella. Now with 25 stores, Lydia’s Lechon keeps up with the ever-changing food cravings of
                                            the Filipinos by expanding its menu to include more local favorites to enjoy for dine-in and delivery.</p>
                                        <a href="http://localhost:8000/our-story" class="link-secondary hvr-shrink">Read more</a>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="intro-content">
                                        <img src="http://localhost:8000/laravel-filemanager/file-manager/Others/image1.png" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>';


        $aboutHTML = '<h2>Overview</h2>

                    <p>Lydia&rsquo;s Lechon, home of the Philippines&rsquo; original boneless lechon stuffed with seafood paella, has been serving its customers for more than 50 years, from a small stall in Baclaran to over 25 stores. Lydia&rsquo;s Lechon aims to bring Filipino dishes like how it&rsquo;s made at home, along with its signature best lechon recipe that&rsquo;s been with the family since 1960&rsquo;s.</p>
                    
                    <h2>History</h2>
                    
                    <p>What started as a small stall beside the Our Lady of Sorrows Church (Baclaran Church), Lydia&rsquo;s has now become one of the country&rsquo;s biggest lechon restaurant with foodcourts and restaurant outlets in Metro Manila. The company owes its success to its founders, Ms. Lydia and Benigno de Roca whose hard work, dedication and unwavering faith in God created the Lydia&rsquo;s Lechon we all know and loved.</p>
                   
                    <p>Lydia&rsquo;s Lechon was started by Ms. Lydia de Roca with her husband Benigno de Roca, Mrs. Lydia who was 17 at that time and with only 500 pesos on hand, set up a small stall besides Our Lady of Sorrows Church where the couple would sell slices of Lechon to the church-goers. Overtime a small stall selling slices of lechon soon finds itself selling whole lechon to the same patrons.</p>
                    
                    <p>In 1986, after selling lechon at the same stall for 21 years, Mr. and Mrs. De Roca finally built their first restaurant along Roxas Boulevard, it was also there that they introduced their well-known boneless lechon with seafood paella.</p>
                    
                    <p>Soon the place grew, attracting famous personalities and businessmen from all over the country. Among them, two owners of a well known hotel who asked Lydia&rsquo;s to supply them with lechon. Their business then grew as more and more hotels would ask for their lechon. Gaining popularity, the restaurant finds itself serving for the former First Lady, Imelda Marcos, when she asked them to prepare on the spot, several whole lechon as part of a celebration for the laying of the cornerstone of Puerto Azul.</p>
                    
                    <p>An achievement made through hardwork and by word-of-mouth, Lydia&rsquo;s continues to grow and by 1989, they established their very first branch in Quezon City in Timog Avenue. Now after over 50 years, Lydia&rsquo;s still continues to serve the Filipinos their special lechon born through years of love and dedication. A recipe which continues unchanged made from the finest ingredients. A symbol of our respect and humility towards the Filipinos and the Philippine&rsquo;s national dish.</p>
                    
                    <h2>Mission</h2>
                    
                    <p>To be the country&rsquo;s preferred lechon house and largest restaurant chain that provides quality Filipino cuisine for great value. To serve our customers in a warm, friendly and efficient manner consistent with Lydia&rsquo;s Lechon brand legacy. Working hand-in-hand with our business partners in creating an environment that enhances the lives of our employees and the community.</p>';

        $contact_us = '<div class="contact-page-details">
							<h2>Contact Information</h2>
							<p>Lorem ipsum dolor sit amet consectetur adipiscing elitsed do eiusmod tempor incididunt utlabore et dolore magna aliqua.</p>
							<div class="gap-20"></div>
							<div class="row">
								<div class="col-md-6">
									<h5>The Office</h5>
									<p><i class="fa fa-map-marker-alt"></i> 5544 Avenue Rosedale, Cöte Saint-Luc QC H4V 2J1</p>
									<p><i class="fa fa-phone"></i> +63 (2) 706-6144 | +63 (2) 706-5796 | +63 (2) 511-0528 </p>
								</div>
								<div class="col-md-6">
									<h5>Office Hour</h5>
									<p><i class="fa fa-clock"></i> Monday - Friday
									<br>9am - 6pm</p>
									<p><i class="fa fa-calendar-alt"></i> Saturday
									<br>9am - 12pm</p>
								</div>
							</div>
						</div>';

        $footerHTML = '    <div class="footer-wrapper">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-lg-4 col-md-12">
                                            <img src="http://localhost:8000/laravel-filemanager/file-manager/lydias-lechon-logo-white.png" />
                                            <br /><br />
                                            <div class="footer-contact">
                                                <p>Get in touch. We are always ready to help you. There are many ways to contact us. You may drop us a line, give us a call or send an email, choose what suits you the most.</p>
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-3">
                                            <div class="footer-contact">
                                                <h6>Links</h6>
                                                <br>
                                                <ul>
                                                    <li><a href="http://localhost:8000/">Home</a></li>
                                                    <li><a href="http://localhost:8000/our-story">Our story</a></li>
                                                    <li><a href="http://localhost:8000/news">News and event</a></li>
                                                    <li><a href="http://localhost:8000/menu">Menu</a></li>
                                                    <li><a href="http://localhost:8000/stores">Stores</a></li>
                                                    <li><a href="http://localhost:8000/contact-us">Contact Us</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-3">
                                            <div class="footer-contact">
                                                <h6>Shopping</h6>
                                                <br>
                                                <ul>
                                                    <li><a href="http://localhost:8000/">Lechon Pricelist</a></li>
                                                    <li><a href="http://localhost:8000/order">Order Online</a></li>
                                                    <li><a href="http://localhost:8000/call-hotline">Call Hotline</a></li>
                                                    <li><a href="http://localhost:8000/sign-up">Sign Up</a></li>
                                                    <li><a href="http://localhost:8000/contact-us">Support</a></li>
                                                    <li><a href="http://localhost:8000/contact-us">Terms and Conditions</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-6">
                                            <div class="footer-contact">
                                                <h6>Contact Us</h6>
                                                <br>
                                                <p>Get in touch awith us via mail phone.We are waiting for your call or message:</p>
                                                <p><strong>hello@lydias-lechon.com</strong></p>
                                                <br>
                                            </div>
                                            <div class="footer-social">
                                                <h6><strong>Follow Us</strong></h6>
                                                <ul>
                                                    <li><a href="#"><span class="fab fa-facebook-square"></span></a></li>
                                                    <li><a href="#"><span class="fab fa-twitter"></span></a></li>
                                                    <li><a href="#"><span class="fab fa-instagram"></span></a></li>
                                                </ul>
                                            </div>
                                        </div>
                        
                                        <div class="col-lg-12">
                                            <div class="copyright">
                                                <hr>
                                                <p>All information, pictures and images on this site are copyrighted material and owned by their respective creators or owners.<br> Copyright &copy; 2020 | Lydia’s Lechon</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        
                            <div class="devs">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <p>Powered by <a href="http://www.webfocus.ph" target="_blank">WebFocus Solutions, Inc.</a></p>
                                        </div>
                                    </div>
                                </div>
                            </div>';

        $menuHTML = '<section>
                        <div class="home-menu">
                        <div class="gap-70">&nbsp;</div>
                        
                        <div class="container">
                        <div class="row">
                        <div class="col-md-12">
                        <h2>our food</h2>
                        
                        <div class="gap-20">&nbsp;</div>
                        
                        <div class="menu-wrapper">
                        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                            <li class="nav-item"><a aria-controls="pills-home" aria-selected="true" class="nav-link active" data-toggle="pill" href="#pills-Best" id="pills-home-tab" role="tab">Best Sellers</a></li>
                            <li class="nav-item"><a aria-controls="pills-home" aria-selected="true" class="nav-link" data-toggle="pill" href="#pills-Appetizer" id="pills-home-tab" role="tab">Appetizer</a></li>
                            <li class="nav-item"><a aria-controls="pills-profile" aria-selected="false" class="nav-link" data-toggle="pill" href="#pills-Specials" id="pills-profile-tab" role="tab">Specials</a></li>
                            <li class="nav-item"><a aria-controls="pills-contact" aria-selected="false" class="nav-link" data-toggle="pill" href="#pills-Favorites" id="pills-contact-tab" role="tab">All-time Favorites</a></li>
                            <li class="nav-item"><a aria-controls="pills-contact" aria-selected="false" class="nav-link" data-toggle="pill" href="#pills-Desserts" id="pills-contact-tab" role="tab">Desserts</a></li>
                            <li class="nav-item"><a aria-controls="pills-contact" aria-selected="false" class="nav-link" data-toggle="pill" href="#pills-Treats" id="pills-contact-tab" role="tab">Merienda Treats</a></li>
                            <li class="nav-item"><a aria-controls="pills-contact" aria-selected="false" class="nav-link" data-toggle="pill" href="#pills-Packages" id="pills-contact-tab" role="tab">Party Packages</a></li>
                            <li class="nav-item"><a aria-controls="pills-contact" aria-selected="false" class="nav-link" data-toggle="pill" href="#pills-Feast" id="pills-contact-tab" role="tab">Boodle Feast</a></li>
                        </ul>
                        
                        <div class="gap-30">&nbsp;</div>
                        
                        <div class="tab-content" id="pills-tabContent">
                        <div aria-labelledby="pills-home-tab" class="tab-pane fade show active" id="pills-Best" role="tabpanel">
                        <div class="row">
                        <div class="col-md-12">
                        <div class="menu-items">
                        <div class="menu-item"><img src="http://localhost:8000/laravel-filemanager/file-manager/Others/food1.jpg" />
                        <p>Lechon Sisig</p>
                        </div>
                        
                        <div class="menu-item"><img src="http://localhost:8000/laravel-filemanager/file-manager/Others/food2.jpg" />
                        <p>Lechon Sisig</p>
                        </div>
                        
                        <div class="menu-item"><img src="http://localhost:8000/laravel-filemanager/file-manager/Others/food3.jpg" />
                        <p>Lechon Sisig</p>
                        </div>
                        
                        <div class="menu-item"><img src="http://localhost:8000/laravel-filemanager/file-manager/Others/food4.jpg" />
                        <p>Lechon Sisig</p>
                        </div>
                        
                        <div class="menu-item"><img src="http://localhost:8000/laravel-filemanager/file-manager/Others/food4.jpg" />
                        <p>Lechon Sisig</p>
                        </div>
                        </div>
                        </div>
                        </div>
                        </div>
                        
                        <div aria-labelledby="pills-home-tab" class="tab-pane fade" id="pills-Appetizer" role="tabpanel">
                        <div class="row">
                        <div class="col-md-12">
                        <div class="menu-items">
                        <div class="menu-item"><img src="http://localhost:8000/laravel-filemanager/file-manager/Others/food1.jpg" />
                        <p>Lechon Sisig</p>
                        </div>
                        
                        <div class="menu-item"><img src="http://localhost:8000/laravel-filemanager/file-manager/Others/food2.jpg" />
                        <p>Lechon Sisig</p>
                        </div>
                        
                        <div class="menu-item"><img src="http://localhost:8000/laravel-filemanager/file-manager/Others/food3.jpg" />
                        <p>Lechon Sisig</p>
                        </div>
                        
                        <div class="menu-item"><img src="http://localhost:8000/laravel-filemanager/file-manager/Others/food4.jpg" />
                        <p>Lechon Sisig</p>
                        </div>
                        
                        <div class="menu-item"><img src="http://localhost:8000/laravel-filemanager/file-manager/Others/food4.jpg" />
                        <p>Lechon Sisig</p>
                        </div>
                        </div>
                        </div>
                        </div>
                        </div>
                        
                        <div aria-labelledby="pills-profile-tab" class="tab-pane fade" id="pills-Specials" role="tabpanel">
                        <div class="row">
                        <div class="col-md-12">
                        <div class="menu-items">
                        <div class="menu-item"><img src="http://localhost:8000/laravel-filemanager/file-manager/Others/food1.jpg" />
                        <p>Lechon Sisig</p>
                        </div>
                        
                        <div class="menu-item"><img src="http://localhost:8000/laravel-filemanager/file-manager/Others/food2.jpg" />
                        <p>Lechon Sisig</p>
                        </div>
                        
                        <div class="menu-item"><img src="http://localhost:8000/laravel-filemanager/file-manager/Others/food3.jpg" />
                        <p>Lechon Sisig</p>
                        </div>
                        
                        <div class="menu-item"><img src="http://localhost:8000/laravel-filemanager/file-manager/Others/food4.jpg" />
                        <p>Lechon Sisig</p>
                        </div>
                        
                        <div class="menu-item"><img src="http://localhost:8000/laravel-filemanager/file-manager/Others/food4.jpg" />
                        <p>Lechon Sisig</p>
                        </div>
                        </div>
                        </div>
                        </div>
                        </div>
                        
                        <div aria-labelledby="pills-contact-tab" class="tab-pane fade" id="pills-Favorites" role="tabpanel">
                        <div class="row">
                        <div class="col-md-12">
                        <div class="menu-items">
                        <div class="menu-item"><img src="http://localhost:8000/laravel-filemanager/file-manager/Others/food1.jpg" />
                        <p>Lechon Sisig</p>
                        </div>
                        
                        <div class="menu-item"><img src="http://localhost:8000/laravel-filemanager/file-manager/Others/food2.jpg" />
                        <p>Lechon Sisig</p>
                        </div>
                        
                        <div class="menu-item"><img src="http://localhost:8000/laravel-filemanager/file-manager/Others/food3.jpg" />
                        <p>Lechon Sisig</p>
                        </div>
                        
                        <div class="menu-item"><img src="http://localhost:8000/laravel-filemanager/file-manager/Others/food4.jpg" />
                        <p>Lechon Sisig</p>
                        </div>
                        
                        <div class="menu-item"><img src="http://localhost:8000/laravel-filemanager/file-manager/Others/food4.jpg" />
                        <p>Lechon Sisig</p>
                        </div>
                        </div>
                        </div>
                        </div>
                        </div>
                        
                        <div aria-labelledby="pills-contact-tab" class="tab-pane fade" id="pills-Desserts" role="tabpanel">
                        <div class="row">
                        <div class="col-md-12">
                        <div class="menu-items">
                        <div class="menu-item"><img src="http://localhost:8000/laravel-filemanager/file-manager/Others/food1.jpg" />
                        <p>Lechon Sisig</p>
                        </div>
                        
                        <div class="menu-item"><img src="http://localhost:8000/laravel-filemanager/file-manager/Others/food2.jpg" />
                        <p>Lechon Sisig</p>
                        </div>
                        
                        <div class="menu-item"><img src="http://localhost:8000/laravel-filemanager/file-manager/Others/food3.jpg" />
                        <p>Lechon Sisig</p>
                        </div>
                        
                        <div class="menu-item"><img src="http://localhost:8000/laravel-filemanager/file-manager/Others/food4.jpg" />
                        <p>Lechon Sisig</p>
                        </div>
                        
                        <div class="menu-item"><img src="http://localhost:8000/laravel-filemanager/file-manager/Others/food4.jpg" />
                        <p>Lechon Sisig</p>
                        </div>
                        </div>
                        </div>
                        </div>
                        </div>
                        
                        <div aria-labelledby="pills-contact-tab" class="tab-pane fade" id="pills-Treats" role="tabpanel">
                        <div class="row">
                        <div class="col-md-12">
                        <div class="menu-items">
                        <div class="menu-item"><img src="http://localhost:8000/laravel-filemanager/file-manager/Others/food1.jpg" />
                        <p>Lechon Sisig</p>
                        </div>
                        
                        <div class="menu-item"><img src="http://localhost:8000/laravel-filemanager/file-manager/Others/food2.jpg" />
                        <p>Lechon Sisig</p>
                        </div>
                        
                        <div class="menu-item"><img src="http://localhost:8000/laravel-filemanager/file-manager/Others/food3.jpg" />
                        <p>Lechon Sisig</p>
                        </div>
                        
                        <div class="menu-item"><img src="http://localhost:8000/laravel-filemanager/file-manager/Others/food4.jpg" />
                        <p>Lechon Sisig</p>
                        </div>
                        
                        <div class="menu-item"><img src="http://localhost:8000/laravel-filemanager/file-manager/Others/food4.jpg" />
                        <p>Lechon Sisig</p>
                        </div>
                        </div>
                        </div>
                        </div>
                        </div>
                        
                        <div aria-labelledby="pills-contact-tab" class="tab-pane fade" id="pills-Packages" role="tabpanel">
                        <div class="row">
                        <div class="col-md-12">
                        <div class="menu-items">
                        <div class="menu-item"><img src="http://localhost:8000/laravel-filemanager/file-manager/Others/food1.jpg" />
                        <p>Lechon Sisig</p>
                        </div>
                        
                        <div class="menu-item"><img src="http://localhost:8000/laravel-filemanager/file-manager/Others/food2.jpg" />
                        <p>Lechon Sisig</p>
                        </div>
                        
                        <div class="menu-item"><img src="http://localhost:8000/laravel-filemanager/file-manager/Others/food3.jpg" />
                        <p>Lechon Sisig</p>
                        </div>
                        
                        <div class="menu-item"><img src="http://localhost:8000/laravel-filemanager/file-manager/Others/food4.jpg" />
                        <p>Lechon Sisig</p>
                        </div>
                        
                        <div class="menu-item"><img src="http://localhost:8000/laravel-filemanager/file-manager/Others/food4.jpg" />
                        <p>Lechon Sisig</p>
                        </div>
                        </div>
                        </div>
                        </div>
                        </div>
                        
                        <div aria-labelledby="pills-contact-tab" class="tab-pane fade" id="pills-Feast" role="tabpanel">
                        <div class="row">
                        <div class="col-md-12">
                        <div class="menu-items">
                        <div class="menu-item"><img src="http://localhost:8000/laravel-filemanager/file-manager/Others/food1.jpg" />
                        <p>Lechon Sisig</p>
                        </div>
                        
                        <div class="menu-item"><img src="http://localhost:8000/laravel-filemanager/file-manager/Others/food2.jpg" />
                        <p>Lechon Sisig</p>
                        </div>
                        
                        <div class="menu-item"><img src="http://localhost:8000/laravel-filemanager/file-manager/Others/food3.jpg" />
                        <p>Lechon Sisig</p>
                        </div>
                        
                        <div class="menu-item"><img src="http://localhost:8000/laravel-filemanager/file-manager/Others/food4.jpg" />
                        <p>Lechon Sisig</p>
                        </div>
                        
                        <div class="menu-item"><img src="http://localhost:8000/laravel-filemanager/file-manager/Others/food4.jpg" />
                        <p>Lechon Sisig</p>
                        </div>
                        </div>
                        </div>
                        </div>
                        </div>
                        </div>
                        </div>
                        
                        <div class="gap-50">&nbsp;</div>
                        <a class="more1" href="#"><strong>download menu</strong></a></div>
                        </div>
                        </div>
                        
                        <div class="gap-70">&nbsp;</div>
                        </div>
                        </section>
                        ';

        $careerHTML = '<section>
                            <div class="content-wrapper">
                            <div class="gap-70"></div>
                                <div class="container">
                                    <div class="row">
                                        <div class="col-lg-10 offset-lg-1">
                                            <center>
                                                <h2>Join Our Team</h2>
                                                <p>Interested in a career or internship with the Lydia\'s Lechon family? We would love to have you join us! Find your next opportunity, new jobs are posted every day. Learn more about the hottest jobs inthe organic industry.</p>
                                                <a href="#" class="btn more1"><i class="fa fa-envelope"></i> Get started now!</a>
                                            </center>
                                        </div>
                                    </div>
                                </div>
                            <div class="gap-70"></div>
                            </div>
                        </section>';

        $pricelistHTML = '<section>
                            <div class="content-wrapper">
                            <div class="gap-70"></div>
                                <div class="container">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <table role="table" class="pricelist-wrapper">
                                              <thead role="rowgroup">
                                                <tr role="row">
                                                  <th role="columnheader" class="main-color border-bottom"><h2>Sizes</h2> <span>whole lechon</span></th>
                                                  <th role="columnheader" class="secondary-color border-bottom"><h2>Price</h2></th>
                                                  <th role="columnheader" colspan="2" class="gray-color border-bottom"><h2>Cooked Weight</h2><span>approximate servings</span></th>
                                                </tr>
                                              </thead>
                                              <tbody role="rowgroup">
                                                <tr role="row">
                                                  <td role="cell" rowspan="3" class="third-color border-bottom size-title"><h2>Small</h2> <span>Add P2,500 for Boneless Lechon stuffed with Seafood Paella</span></td>
                                                  <td role="cell" class="fourth-color">Php 7,800</td>
                                                  <td role="cell" class="gray-color">De Leche <br><span>Subject to Availability</span></td>
                                                  <td role="cell" class="gray-color">15-20 pax</td>
                                                </tr>
                                                <tr role="row">
                                                  <td role="cell" class="fourth-color">Php 8,800</td>
                                                  <td role="cell" class="gray-color">8-9 Kg</td>
                                                  <td role="cell" class="gray-color">20-25 pax</td>
                                                </tr>
                                                <tr role="row">
                                                  <td role="cell" class="fourth-color">Php 9,800</td>
                                                  <td role="cell" class="gray-color border-bottom">10-12 Kg</td>
                                                  <td role="cell" class="gray-color border-bottom">25-30 pax</td>
                                                </tr>
                                                <tr role="row">
                                                  <td role="cell" rowspan="3" class="third-color border-bottom size-title"><h2>Medium</h2><span>Add P3,500 for Boneless Lechon stuffed with Seafood Paella</span></td>
                                                  <td role="cell" class="fourth-color">Php 10,800</td>
                                                  <td role="cell" class="gray-color">13-15 Kg</td>
                                                  <td role="cell" class="gray-color">35-40 pax</td>
                                                </tr>
                                                <tr role="row">
                                                  <td role="cell" class="fourth-color">Php 11,800</td>
                                                  <td role="cell" class="gray-color">16 Kg</td>
                                                  <td role="cell" class="gray-color">45-50 pax</td>
                                                </tr>
                                                <tr role="row">
                                                  <td role="cell" class="fourth-color">Php 12,800</td>
                                                  <td role="cell" class="gray-color">17-19 Kg</td>
                                                  <td role="cell" class="gray-color">60-70 pax</td>
                                                </tr>
                                                <tr role="row">
                                                  <td role="cell" rowspan="3" class="third-color border-bottom size-title"><h2>Large</h2><span>Add P4,500 for Boneless Lechon stuffed with Seafood Paella</span></td>
                                                  <td role="cell" class="fourth-color">Php 13,800</td>
                                                  <td role="cell" class="gray-color">20-22 Kg</td>
                                                  <td role="cell" class="gray-color">55-90 pax</td>
                                                </tr>
                                                <tr role="row">
                                                  <td role="cell" class="fourth-color">Php 14,800</td>
                                                  <td role="cell" class="gray-color">23-25 Kg</td>
                                                  <td role="cell" class="gray-color">95-100 pax</td>
                                                </tr>
                                                <tr role="row">
                                                  <td role="cell" class="fourth-color">Php 15,800</td>
                                                  <td role="cell" class="gray-color">26-30 Kg</td>
                                                  <td role="cell" class="gray-color">110-130 pax</td>
                                                </tr>
                                                <tr role="row">
                                                  <td role="cell" class="third-color border-bottom size-title"><h2>X-Large</h2><span>Add P5,500 for Boneless Lechon stuffed with Seafood Paella</span></td>
                                                  <td role="cell" class="fourth-color border-bottom">Php 16,800</td>
                                                  <td role="cell" class="gray-color border-bottom">32-35 Kg</td>
                                                  <td role="cell" class="gray-color border-bottom">140-160 pax</td>
                                                </tr>
                                                <tr role="row">
                                                  <td role="cell" class="third-color border-bottom size-title"><h2>Lechon-in-a-box</h2><span></span></td>
                                                  <td role="cell" class="fourth-color border-bottom">Php 2,500</td>
                                                  <td role="cell" class="gray-color border-bottom">-</td>
                                                  <td role="cell" class="gray-color border-bottom">10-12 pax</td>
                                                </tr>
                                                <tr role="row">
                                                  <td role="cell" class="main-color border-bottom baka size-title"><h2>Lechon Baka</h2></td>
                                                  <td role="cell" class="secondary-color">Php 52,000</td>
                                                  <td role="cell" class="gray-color">100-120 Kg</td>
                                                  <td role="cell" class="gray-color">150-200 pax</td>
                                                </tr>
                                              </tbody>
                                            </table>
                                            <div class="notes-pricelist">
                                                <div class="notes-pricelist-info">
                                                    <ul>
                                                        <li>Deliveries are for areas within Metro Manila only</li>
                                                        <li>For deliveries outside Metro Manila please call hotline at 939-1221</li>
                                                        <li>Orders should be made at least 2-3 days prior to delivery date</li>
                                                        <li>We accept all major credit/debit card payment</li>
                                                    </ul>
                                                </div>
                                                <div class="notes-pricelist-button">
                                                    <a href="http://localhost:8000/storage/file-manager/file-manager/Files/Lydias-Lechon-Menu.pdf" class="btn btn-primary more1">Download pricelist</a>
                                                    <a href="http://localhost:8000/order" class="btn btn-primary more2">order now</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <div class="gap-70"></div>
                            </div>
                        </section>';

        $homeAdsHTML = '<section>
                            <div class="ads-wrapper">
                            
                            <div class="container">
                            <div class="row">
                            <div class="col-md-6"><a href="#"><img src="http://localhost:8000/laravel-filemanager/file-manager/Others/ads/image1.jpg" /></a></div>
                            
                            <div class="col-md-6"><a href="#"><img src="http://localhost:8000/laravel-filemanager/file-manager/Others/ads/image1.jpg" /></a></div>
                            </div>
                            </div>
                            </div>
                        </section>';

        $storeHTML = '<div class="row">
        
                        <div class="col-md-6">
                            <div class="store-location-item">
                                <div class="row">
                                    <div class="col-md-7">
                                        <div class="store-location-title"><h5><strong>Baclaran Branch</strong></h5></div>
                                        <div class="store-location-address">551 Service Road, Roxas Blvd. Baclaran, Parañaque, Metro Manila</div>
                                        <div class="store-location-contact">+632 8851 2987 /+632 8851 2988 / +632 8851 2989</div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="store-location-map">
                                            <span><a href="https://maps.google.com/maps?saddr=current+location&daddr=551+Service+Road%2C+Roxas+Blvd.%2C+Baclaran%2C+Para%C3%B1aque%2C+Metro+Manila%2C+Philippines+%2C++" target="_blank"><i class="fa fa-map-marker-alt"></i><br>Directions</a></span>
                                            <span><a href="https://maps.google.com/?q=551+Service+Road%2C+Roxas+Blvd.%2C+Baclaran%2C+Para%C3%B1aque%2C+Metro+Manila%2C+Philippines+%2C++" target="_blank"><i class="fa fa-location-arrow"></i><br>View Full Map</a></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="store-location-item">
                                <div class="row">
                                    <div class="col-md-7">
                                        <div class="store-location-title"><h5><strong>Cash & Carry Branch</strong></h5></div>
                                        <div class="store-location-address">20 Filmore St., Palanan Makati City, Metro Manila</div>
                                        <div class="store-location-contact">+632 8475 7558</div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="store-location-map">
                                            <span><a href="https://maps.google.com/maps?saddr=current+location&daddr=20+Filmore+St.%2C+Palanan%2C+Makati+City%2C+Metro+Manila%2C+Philippines+%2C++" target="_blank"><i class="fa fa-map-marker-alt"></i><br>Directions</a></span>
                                            <span><a href="https://maps.google.com/?q=20+Filmore+St.%2C+Palanan%2C+Makati+City%2C+Metro+Manila%2C+Philippines+%2C++" target="_blank"><i class="fa fa-location-arrow"></i><br>View Full Map</a></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="store-location-item">
                                <div class="row">
                                    <div class="col-md-7">
                                        <div class="store-location-title"><h5><strong>SM Sucat Branch</strong></h5></div>
                                        <div class="store-location-address">SMFC, New Sucat Road, San Dionisio Sucat, Parañaque, Metro Manila</div>
                                        <div class="store-location-contact">+632 8829 4460</div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="store-location-map">
                                            <span><a href="https://maps.google.com/maps?saddr=current+location&daddr=SMFC%2C+New+Sucat+Road%2C+San+Dionisio%2C+Sucat%2C+Para%C3%B1aque%2C+Metro++Manila%2C+Philippines+%2C++" target="_blank"><i class="fa fa-map-marker-alt"></i><br>Directions</a></span>
                                            <span><a href="https://maps.google.com/?q=SMFC%2C+New+Sucat+Road%2C+San+Dionisio%2C+Sucat%2C+Para%C3%B1aque%2C+Metro++Manila%2C+Philippines+%2C++" target="_blank"><i class="fa fa-location-arrow"></i><br>View Full Map</a></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="store-location-item">
                                <div class="row">
                                    <div class="col-md-7">
                                        <div class="store-location-title"><h5><strong>SM Bacoor Branch</strong></h5></div>
                                        <div class="store-location-address">SMFC Aguinaldo Hi-way Corner Terona, Bacoor Cavite</div>
                                        <div class="store-location-contact">046 417 2987</div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="store-location-map">
                                            <span><a href="https://maps.google.com/maps?saddr=current+location&daddr=SMFC+Aguinaldo+Hi-way+Corner%2C+Terona%2C+Bacoor+Cavite%2C+Philippines+%2C++" target="_blank"><i class="fa fa-map-marker-alt"></i><br>Directions</a></span>
                                            <span><a href="https://maps.google.com/?q=SMFC+Aguinaldo+Hi-way+Corner%2C+Terona%2C+Bacoor+Cavite%2C+Philippines+%2C++" target="_blank"><i class="fa fa-location-arrow"></i><br>View Full Map</a></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="store-location-item">
                                <div class="row">
                                    <div class="col-md-7">
                                        <div class="store-location-title"><h5><strong>SM Dasmariñas Branch</strong></h5></div>
                                        <div class="store-location-address">FS#1 Foodcourt SM City Dasmariñas Brgy. Sampaloc Dasmariñas Cavite City</div>
                                        <div class="store-location-contact">046 416 4454</div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="store-location-map">
                                            <span><a href="https://maps.google.com/maps?saddr=current+location&daddr=FS%231+Foodcourt+SM+City+Dasmari%C3%B1as+%2C+Brgy.+Sampaloc+Dasmari%C3%B1as+Cavite+City%2C+Philippines+%2C++" target="_blank"><i class="fa fa-map-marker-alt"></i><br>Directions</a></span>
                                            <span><a href="https://maps.google.com/?q=FS%231+Foodcourt+SM+City+Dasmari%C3%B1as+%2C+Brgy.+Sampaloc+Dasmari%C3%B1as+Cavite+City%2C+Philippines+%2C++" target="_blank"><i class="fa fa-location-arrow"></i><br>View Full Map</a></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="store-location-item">
                                <div class="row">
                                    <div class="col-md-7">
                                        <div class="store-location-title"><h5><strong>Landmark Makati</strong></h5></div>
                                        <div class="store-location-address">Stall #14 Landmark Makati Foodcourt, Food Center Ayala Center, Makati City, Metro Manila</div>
                                        <div class="store-location-contact">+632 8556 5525</div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="store-location-map">
                                            <span><a href="https://maps.google.com/maps?saddr=current+location&daddr=Stall+%2314+Landmark+Makati+Foodcourt%2C+Food+Center+%2C+Ayala+Center%2C+Makati+City%2C+Metro++Manila%2C+Philippines+%2C++" target="_blank"><i class="fa fa-map-marker-alt"></i><br>Directions</a></span>
                                            <span><a href="https://maps.google.com/?q=Stall+%2314+Landmark+Makati+Foodcourt%2C+Food+Center+%2C+Ayala+Center%2C+Makati+City%2C+Metro++Manila%2C+Philippines+%2C++" target="_blank"><i class="fa fa-location-arrow"></i><br>View Full Map</a></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="store-location-item">
                                <div class="row">
                                    <div class="col-md-7">
                                        <div class="store-location-title"><h5><strong>Timog Branch</strong></h5></div>
                                        <div class="store-location-address">116 Timog Avenue, Cor. 11th Jamboree St. Quezon City, Metro Manila</div>
                                        <div class="store-location-contact">8355-9149</div>
                                        <div class="store-location-contact">+63966 752 9519</div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="store-location-map">
                                            <span><a href="https://maps.google.com/maps?saddr=current+location&daddr=116+Timog+Avenue%2C+Cor.+11th+Jamboree+St.%2C+Quezon+City%2C+Metro+Manila%2C+Philippines+%2C++" target="_blank"><i class="fa fa-map-marker-alt"></i><br>Directions</a></span>
                                            <span><a href="https://maps.google.com/?q=116+Timog+Avenue%2C+Cor.+11th+Jamboree+St.%2C+Quezon+City%2C+Metro+Manila%2C+Philippines+%2C++" target="_blank"><i class="fa fa-location-arrow"></i><br>View Full Map</a></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="store-location-item">
                                <div class="row">
                                    <div class="col-md-7">
                                        <div class="store-location-title"><h5><strong>Roces Branch</strong></h5></div>
                                        <div class="store-location-address">49-Don A. Roces Avenue, Corner Scout Reyes Quezon City, Metro Manila, Philippines</div>
                                        <div class="store-location-contact">+632 8376 9016</div>
                                        <div class="store-location-contact">(+632) 376-9016</div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="store-location-map">
                                            <span><a href="https://maps.google.com/maps?saddr=current+location&daddr=49-Don+A.+Roces+Avenue%2C+Corner+Scout+Reyes%2C+Quezon+City%2C+Metro+Manila%2C+Philippines+%2C++" target="_blank"><i class="fa fa-map-marker-alt"></i><br>Directions</a></span>
                                            <span><a href="https://maps.google.com/?q=49-Don+A.+Roces+Avenue%2C+Corner+Scout+Reyes%2C+Quezon+City%2C+Metro+Manila%2C+Philippines+%2C++" target="_blank"><i class="fa fa-location-arrow"></i><br>View Full Map</a></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="store-location-item">
                                <div class="row">
                                    <div class="col-md-7">
                                        <div class="store-location-title"><h5><strong>Marcos Highway Branch</strong></h5></div>
                                        <div class="store-location-address">24 Marcos Hiway Corner Pambuli Street Marikina City, Metro Manila, Philippines</div>
                                        <div class="store-location-contact">+632 8682 8927</div>
                                        <div class="store-location-contact">(+632) 646-0871</div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="store-location-map">
                                            <span><a href="https://maps.google.com/maps?saddr=current+location&daddr=24+Marcos+Hiway+Corner+Pambuli+Street%2C+Marikina+City%2C+Metro+Manila%2C+Philippines+%2C++" target="_blank"><i class="fa fa-map-marker-alt"></i><br>Directions</a></span>
                                            <span><a href="https://maps.google.com/?q=24+Marcos+Hiway+Corner+Pambuli+Street%2C+Marikina+City%2C+Metro+Manila%2C+Philippines+%2C++" target="_blank"><i class="fa fa-location-arrow"></i><br>View Full Map</a></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="store-location-item">
                                <div class="row">
                                    <div class="col-md-7">
                                        <div class="store-location-title"><h5><strong>Pasig Branch</strong></h5></div>
                                        <div class="store-location-address">E. Rodriguez Jr. Avenue Corner C.J. Caparas St. Brgy. Ugong, Pasig City, Metro Manila, Philippines</div>
                                        <div class="store-location-contact">+632 8671 9023</div>
                                        <div class="store-location-contact">(+632) 671 9053</div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="store-location-map">
                                            <span><a href="https://maps.google.com/maps?saddr=current+location&daddr=E.+Rodriguez+Jr.+Avenue+Corner+C.J.+Caparas+St.%2C+Brgy.+Ugong%2C+Pasig+City%2C+Metro+Manila%2C+Philippines+%2C++" target="_blank"><i class="fa fa-map-marker-alt"></i><br>Directions</a></span>
                                            <span><a href="https://maps.google.com/?q=E.+Rodriguez+Jr.+Avenue+Corner+C.J.+Caparas+St.%2C+Brgy.+Ugong%2C+Pasig+City%2C+Metro+Manila%2C+Philippines+%2C++" target="_blank"><i class="fa fa-location-arrow"></i><br>View Full Map</a></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="store-location-item">
                                <div class="row">
                                    <div class="col-md-7">
                                        <div class="store-location-title"><h5><strong>Commonwealth Branch</strong></h5></div>
                                        <div class="store-location-address">77 Fairview Avenue, Fairview Subdivision Quezon City, Metro Manila, Philippines</div>
                                        <div class="store-location-contact">+632 8935 5095</div>
                                        <div class="store-location-contact">(+632) 935-5095</div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="store-location-map">
                                            <span><a href="https://maps.google.com/maps?saddr=current+location&daddr=77+Fairview+Avenue%2C+Fairview+Subdivision%2C+Quezon+City%2C+Metro+Manila%2C+Philippines+%2C++" target="_blank"><i class="fa fa-map-marker-alt"></i><br>Directions</a></span>
                                            <span><a href="https://maps.google.com/?q=77+Fairview+Avenue%2C+Fairview+Subdivision%2C+Quezon+City%2C+Metro+Manila%2C+Philippines+%2C++" target="_blank"><i class="fa fa-location-arrow"></i><br>View Full Map</a></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="store-location-item">
                                <div class="row">
                                    <div class="col-md-7">
                                        <div class="store-location-title"><h5><strong>SM Taytay Branch</strong></h5></div>
                                        <div class="store-location-contact">+632 8671 224</div>
                                        <div class="store-location-address">SMFC, FS#4 SM City Taytay, Manila East Road, Dolores Taytay, Rizal, Philippines</div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="store-location-map">
                                            <span><a href="https://maps.google.com/maps?saddr=current+location&daddr=SMFC%2C+FS%234+SM+City+Taytay%2C+Manila+East+Road%2C+Dolores%2C+Taytay%2C+Rizal%2C+Philippines+%2C++" target="_blank"><i class="fa fa-map-marker-alt"></i><br>Directions</a></span>
                                            <span><a href="https://maps.google.com/?q=SMFC%2C+FS%234+SM+City+Taytay%2C+Manila+East+Road%2C+Dolores%2C+Taytay%2C+Rizal%2C+Philippines+%2C++" target="_blank"><i class="fa fa-location-arrow"></i><br>View Full Map</a></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="store-location-item">
                                <div class="row">
                                    <div class="col-md-7">
                                        <div class="store-location-title"><h5><strong>SM Megamall Branch</strong></h5></div>
                                        <div class="store-location-address">SMFC, Julia Vargas Ave., Corner EDSA Mandaluyong City, Metro Manila, Philippines</div>
                                        <div class="store-location-contact">+632 8633 4966</div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="store-location-map">
                                            <span><a href="https://maps.google.com/maps?saddr=current+location&daddr=SMFC%2C+Julia+Vargas+Ave.%2C+Corner+EDSA%2C+Mandaluyong+City%2C+Metro+Manila%2C+Philippines+%2C++" target="_blank"><i class="fa fa-map-marker-alt"></i><br>Directions</a></span>
                                            <span><a href="https://maps.google.com/?q=SMFC%2C+Julia+Vargas+Ave.%2C+Corner+EDSA%2C+Mandaluyong+City%2C+Metro+Manila%2C+Philippines+%2C++" target="_blank"><i class="fa fa-location-arrow"></i><br>View Full Map</a></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="store-location-item">
                                <div class="row">
                                    <div class="col-md-7">
                                        <div class="store-location-title"><h5><strong>SM North Edsa Branch</strong></h5></div>
                                        <div class="store-location-address">SMFC, SM City North Avenue Corner EDSA, Quezon City, Metro Manila, Philippines</div>
                                        <div class="store-location-contact">+632 8926 1620</div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="store-location-map">
                                            <span><a href="https://maps.google.com/maps?saddr=current+location&daddr=SMFC%2C+SM+City+North+Avenue+Corner%2C+EDSA%2C+Quezon+City%2C+Metro+Manila%2C+Philippines+%2C++" target="_blank"><i class="fa fa-map-marker-alt"></i><br>Directions</a></span>
                                            <span><a href="https://maps.google.com/?q=SMFC%2C+SM+City+North+Avenue+Corner%2C+EDSA%2C+Quezon+City%2C+Metro+Manila%2C+Philippines+%2C++" target="_blank"><i class="fa fa-location-arrow"></i><br>View Full Map</a></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="store-location-item">
                                <div class="row">
                                    <div class="col-md-7">
                                        <div class="store-location-title"><h5><strong>SM San Lazaro Branch</strong></h5></div>
                                        <div class="store-location-address">SMFC, Felix Huertas St., Corner Lacson Ave. Sta. Cruz, Manila, Philippines</div>
                                        <div class="store-location-contact">+632 8353 2637</div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="store-location-map">
                                            <span><a href="https://maps.google.com/maps?saddr=current+location&daddr=SMFC%2C+Felix+Huertas+St.%2C+Corner+Lacson+Ave.%2C+Sta.+Cruz%2C+Manila%2C+Philippines+%2C++" target="_blank"><i class="fa fa-map-marker-alt"></i><br>Directions</a></span>
                                            <span><a href="https://maps.google.com/?q=SMFC%2C+Felix+Huertas+St.%2C+Corner+Lacson+Ave.%2C+Sta.+Cruz%2C+Manila%2C+Philippines+%2C++" target="_blank"><i class="fa fa-location-arrow"></i><br>View Full Map</a></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="store-location-item">
                                <div class="row">
                                    <div class="col-md-7">
                                        <div class="store-location-title"><h5><strong>SM Fairview Branch</strong></h5></div>
                                        <div class="store-location-contact"><i class="fa fa-mobile-alt"></i>+632 8939 5743</div>
                                        <div class="store-location-address"><i class="fa fa-map-marker-alt"></i>SMFC, SM Fairview, Quirino Highway, Corner Regalado Ave. Novaliches Quezon City, Metro Manila, Philippines</div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="store-location-map">
                                            <span><a href="https://maps.google.com/maps?saddr=current+location&daddr=SMFC%2C+SM+Fairview%2C+Quirino+Highway%2C+Corner+Regalado+Ave.+Novaliches%2C+Quezon+City%2C+Metro+Manila%2C+Philippines+%2C++" target="_blank"><i class="fa fa-map-marker-alt"></i><br>Directions</a></span>
                                            <span><a href="https://maps.google.com/?q=SMFC%2C+SM+Fairview%2C+Quirino+Highway%2C+Corner+Regalado+Ave.+Novaliches%2C+Quezon+City%2C+Metro+Manila%2C+Philippines+%2C++" target="_blank"><i class="fa fa-location-arrow"></i><br>View Full Map</a></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="store-location-item">
                                <div class="row">
                                    <div class="col-md-7">
                                        <div class="store-location-title"><h5><strong>168 MALL</strong></h5></div>
                                        <div class="store-location-contact"><i class="fa fa-mobile-alt"></i>+632 8254 1907</div>
                                        <div class="store-location-address"><i class="fa fa-map-marker-alt"></i>3rd Floor 168 Shopping Mall, Sta. Elena St. Binondo, Metro Manila, Philippines</div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="store-location-map">
                                            <span><a href="https://maps.google.com/maps?saddr=current+location&daddr=3rd+Floor++168+Shopping+Mall%2C+Sta.+Elena+St.%2C+Binondo%2C+Metro+Manila%2C+Philippines+%2C++" target="_blank"><i class="fa fa-map-marker-alt"></i><br>Directions</a></span>
                                            <span><a href="https://maps.google.com/?q=3rd+Floor++168+Shopping+Mall%2C+Sta.+Elena+St.%2C+Binondo%2C+Metro+Manila%2C+Philippines+%2C++" target="_blank"><i class="fa fa-location-arrow"></i><br>View Full Map</a></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="store-location-item">
                                <div class="row">
                                    <div class="col-md-7">
                                        <div class="store-location-title"><h5><strong>Manila Doctor’s Hospital</strong></h5></div>
                                        <div class="store-location-contact"><i class="fa fa-mobile-alt"></i>+632 8536 1639</div>
                                        <div class="store-location-address"><i class="fa fa-map-marker-alt"></i>667 United Nations Ave, Ermita, Manila</div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="store-location-map">
                                            <span><a href="https://maps.google.com/maps?saddr=current+location&daddr=667+United+Nations+Ave%2C+Ermita%2C+Manila+Metro+Manila%2C++1000" target="_blank"><i class="fa fa-map-marker-alt"></i><br>Directions</a></span>
                                            <span><a href="https://maps.google.com/?q=667+United+Nations+Ave%2C+Ermita%2C+Manila+Metro+Manila%2C++1000" target="_blank"><i class="fa fa-location-arrow"></i><br>View Full Map</a></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                       
                       </div>';

        $homePromoAdsHTML = '<p><img alt="" src="http://localhost:8000/laravel-filemanager/file-manager/Others/lydias-lechon-promo.jpg" /></p>';

        $importantRemindersHTML = '<ul class="mb-4">
                        <li>Deliveries are for areas within Metro Manila only</li>
                        <li>For deliveries outside Metro Manila please call our hotline at 939-1221 or 851-2987</li>
                        <li>Orders should be made at least 2 days or 48 hours prior to delivery date</li>
                        <li>International orders should be made at least 3 days or 72 hours prior to delivery date</li>
                        <li>Changes and cancellation of orders can be made up to 30 hours prior to delivery date and should be course through our hotline at 939-1221</li>
                        <li>Cancellation of orders is subject for review by Lydia’s Lechon Management</li>
                        <li>You may settle your payment using the following options</li>
                    </ul>
                    <div class="row p-3">
                        <div class="col-md-4">
                            <p><strong>Credit / Debit Card</strong></p>
                            <img src="http://localhost:8000/laravel-filemanager/file-manager/Others/pay1.png" />
                        </div>
                        <div class="col-md-4">
                            <p><strong>Bank Deposit</strong></p>
                            <img src="http://localhost:8000/laravel-filemanager/file-manager/Others/pay2.png" />
                            <small>Lydia\'s Lechon Quezon City, Inc.</small>
                            <small>Account # 481-3481062510</small>
                            <img src="http://localhost:8000/laravel-filemanager/file-manager/Others/pay3.png" />
                            <small>Lydia\'s Lechon Quezon City, Inc.</small>
                            <small>Account # 162-004-2132</small>
                        </div>
                        <div class="col-md-4">
                            <p><strong>Payment Center</strong></p>
                            <img src="http://localhost:8000/laravel-filemanager/file-manager/Others/pay4.png" />
                        </div>
                    </div>';

        $newsListingContent = '';
        $pages = [
            [
                'parent_page_id' => 0,
                'album_id' => 1,
                'slug' => 'home',
                'name' => 'Home',
                'label' => 'Home',
                'contents' => $homeHTML,
                'status' => 'PUBLISHED',
                'page_type' => 'default',
                'image_url' => '',
                'meta_title' => 'Home',
                'meta_keyword' => 'home',
                'meta_description' => 'Home page',
                'user_id' => 1,
                'template' => 'home',
                'created_at' => '2019-10-06 20:31:26',
                'updated_at' => '2019-10-06 20:31:26'
            ],
            [
                'parent_page_id' => 0,
                'album_id' => 0,
                'slug' => 'our-story',
                'name' => 'Our Story',
                'label' => 'Our Story',
                'contents' => $aboutHTML,
                'status' => 'PUBLISHED',
                'page_type' => 'standard',
                'image_url' => \URL::to('/').'/theme/lydias/images/banners/subimage1.jpg',
                'meta_title' => 'About Us',
                'meta_keyword' => 'About Us',
                'meta_description' => 'About Us page',
                'user_id' => 1,
                'template' => '',
                'created_at' => '2019-10-06 20:31:26',
                'updated_at' => '2019-10-06 20:31:26'
            ],

            [
                'parent_page_id' => 0,
                'album_id' => 0,
                'slug' => 'contact-us',
                'name' => 'Contact Us',
                'label' => 'Contact Us',
                'contents' => $contact_us,
                'status' => 'PUBLISHED',
                'page_type' => 'standard',
                'image_url' => \URL::to('/').'/theme/lydias/images/banners/subimage1.jpg',
                'meta_title' => 'Contact Us',
                'meta_keyword' => 'Contact Us',
                'meta_description' => 'Contact Us page',
                'user_id' => 1,
                'template' => 'contact-us',
                'created_at' => '2019-10-06 20:31:26',
                'updated_at' => '2019-10-06 20:31:26'
            ],
            [
                'parent_page_id' => 0,
                'album_id' => 0,
                'slug' => 'news',
                'name' => 'News',
                'label' => 'Blogs',
                'contents' => '',
                'status' => 'PUBLISHED',
                'page_type' => 'default',
                'image_url' => \URL::to('/').'/theme/lydias/images/banners/subimage1.jpg',
                'meta_title' => 'News',
                'meta_keyword' => 'news',
                'meta_description' => 'News page',
                'user_id' => 1,
                'template' => 'news',
                'created_at' => '2019-10-06 20:31:26',
                'updated_at' => '2019-10-06 20:31:26'
            ],
            [
                'parent_page_id' => 0,
                'album_id' => 0,
                'slug' => 'membership',
                'name' => 'Membership',
                'label' => 'Membership',
                'contents' => '<h1>Membership goes here</h1><p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>',
                'status' => 'PUBLISHED',
                'page_type' => 'standard',
                'image_url' => \URL::to('/').'/theme/lydias/images/banners/sub/image1.jpg',
                'meta_title' => 'Membership',
                'meta_keyword' => 'Membership',
                'meta_description' => 'Membership page',
                'user_id' => 1,
                'template' => '',
                'created_at' => '2019-10-06 20:31:26',
                'updated_at' => '2019-10-06 20:31:26'
            ],
            [
                'parent_page_id' => 0,
                'album_id' => 0,
                'slug' => 'footer',
                'name' => 'Footer',
                'label' => 'footer',
                'contents' => $footerHTML,
                'status' => 'PUBLISHED',
                'page_type' => 'default',
                'image_url' => '',
                'meta_title' => '',
                'meta_keyword' => '',
                'meta_description' => '',
                'user_id' => 1,
                'template' => '',
                'created_at' => '2019-10-06 20:31:26',
                'updated_at' => '2019-10-06 20:31:26'
            ],
            [
                'parent_page_id' => 0,
                'album_id' => 0,
                'slug' => 'menu',
                'name' => 'Menu',
                'label' => 'Menu',
                'contents' => $menuHTML,
                'status' => 'PUBLISHED',
                'page_type' => 'default',
                'image_url' => \URL::to('/').'/theme/lydias/images/banners/subimage1.jpg',
                'meta_title' => '',
                'meta_keyword' => '',
                'meta_description' => '',
                'user_id' => 1,
                'template' => '',
                'created_at' => '2019-10-06 20:31:26',
                'updated_at' => '2019-10-06 20:31:26'
            ],
            [
                'parent_page_id' => 0,
                'album_id' => 0,
                'slug' => 'stores',
                'name' => 'Stores',
                'label' => 'Stores',
                'contents' => $storeHTML,
                'status' => 'PUBLISHED',
                'page_type' => 'standard',
                'image_url' => \URL::to('/').'/theme/lydias/images/banners/subimage1.jpg',
                'meta_title' => '',
                'meta_keyword' => '',
                'meta_description' => '',
                'user_id' => 1,
                'template' => '',
                'created_at' => '2019-10-06 20:31:26',
                'updated_at' => '2019-10-06 20:31:26'
            ],
            [
                'parent_page_id' => 0,
                'album_id' => 0,
                'slug' => 'careers',
                'name' => 'Careers',
                'label' => 'Careers',
                'contents' => $careerHTML,
                'status' => 'PUBLISHED',
                'page_type' => 'default',
                'image_url' => \URL::to('/').'/theme/lydias/images/banners/subimage1.jpg',
                'meta_title' => '',
                'meta_keyword' => '',
                'meta_description' => '',
                'user_id' => 1,
                'template' => '',
                'created_at' => '2019-10-06 20:31:26',
                'updated_at' => '2019-10-06 20:31:26'
            ],
            [
                'parent_page_id' => 0,
                'album_id' => 0,
                'slug' => 'lechon-pricelist',
                'name' => 'Lechon Pricelist',
                'label' => 'Lechon Pricelist',
                'contents' => $pricelistHTML,
                'status' => 'PUBLISHED',
                'page_type' => 'standard',
                'image_url' => \URL::to('/').'/theme/lydias/images/banners/subimage1.jpg',
                'meta_title' => '',
                'meta_keyword' => '',
                'meta_description' => '',
                'user_id' => 1,
                'template' => '',
                'created_at' => '2019-10-06 20:31:26',
                'updated_at' => '2019-10-06 20:31:26'
            ],
            [
                'parent_page_id' => 0,
                'album_id' => 0,
                'slug' => 'call-hotline',
                'name' => 'Call Hotline',
                'label' => 'Call Hotline',
                'contents' => '',
                'status' => 'PUBLISHED',
                'page_type' => 'standard',
                'image_url' => \URL::to('/').'/theme/lydias/images/banners/subimage1.jpg',
                'meta_title' => '',
                'meta_keyword' => '',
                'meta_description' => '',
                'user_id' => 1,
                'template' => '',
                'created_at' => '2019-10-06 20:31:26',
                'updated_at' => '2019-10-06 20:31:26'
            ],
            [
                'parent_page_id' => 0,
                'album_id' => 0,
                'slug' => 'terms-and-conditions',
                'name' => 'Terms and Conditions',
                'label' => 'Terms and Conditions',
                'contents' => '',
                'status' => 'PUBLISHED',
                'page_type' => 'default',
                'image_url' => \URL::to('/').'/theme/lydias/images/banners/subimage1.jpg',
                'meta_title' => '',
                'meta_keyword' => '',
                'meta_description' => '',
                'user_id' => 1,
                'template' => '',
                'created_at' => '2019-10-06 20:31:26',
                'updated_at' => '2019-10-06 20:31:26'
            ],
            [
                'parent_page_id' => 0,
                'album_id' => 0,
                'slug' => 'register',
                'name' => 'Register',
                'label' => 'Register',
                'contents' => '',
                'status' => 'PUBLISHED',
                'page_type' => 'uneditable',
                'image_url' => \URL::to('/').'/theme/lydias/images/banners/subimage1.jpg',
                'meta_title' => '',
                'meta_keyword' => '',
                'meta_description' => '',
                'user_id' => 1,
                'template' => '',
                'created_at' => '2019-10-06 20:31:26',
                'updated_at' => '2019-10-06 20:31:26'
            ],
            [
                'parent_page_id' => 0,
                'album_id' => 0,
                'slug' => 'order',
                'name' => 'Order Online',
                'label' => 'Order Online',
                'contents' => '',
                'status' => 'PUBLISHED',
                'page_type' => 'uneditable',
                'image_url' => \URL::to('/').'/theme/lydias/images/banners/subimage1.jpg',
                'meta_title' => '',
                'meta_keyword' => '',
                'meta_description' => '',
                'user_id' => 1,
                'template' => '',
                'created_at' => '2019-10-06 20:31:26',
                'updated_at' => '2019-10-06 20:31:26'
            ],
            [
                'parent_page_id' => 0,
                'album_id' => 1,
                'slug' => 'home-ads',
                'name' => 'Home Ads',
                'label' => 'Home Ads',
                'contents' => $homeAdsHTML,
                'status' => 'PUBLISHED',
                'page_type' => 'default',
                'image_url' => '',
                'meta_title' => 'Home',
                'meta_keyword' => 'home',
                'meta_description' => 'Home',
                'user_id' => 1,
                'template' => 'home ads',
                'created_at' => '2019-10-06 20:31:26',
                'updated_at' => '2019-10-06 20:31:26'
            ],
            [
                'parent_page_id' => 0,
                'album_id' => 1,
                'slug' => 'promo-ads',
                'name' => 'Promo Ads',
                'label' => 'Promo Ads',
                'contents' => $homePromoAdsHTML,
                'status' => 'PRIVATE',
                'page_type' => 'standard',
                'image_url' => '',
                'meta_title' => 'Promo',
                'meta_keyword' => 'Promo',
                'meta_description' => 'Promo',
                'user_id' => 1,
                'template' => 'promo ads',
                'created_at' => '2019-10-06 20:31:26',
                'updated_at' => '2019-10-06 20:31:26'
            ],
            [
                'parent_page_id' => 0,
                'album_id' => 1,
                'slug' => 'important-reminders',
                'name' => 'Important Reminders',
                'label' => 'Important Reminders',
                'contents' => $importantRemindersHTML,
                'status' => 'PRIVATE',
                'page_type' => 'default',
                'image_url' => '',
                'meta_title' => 'Important Reminders',
                'meta_keyword' => 'Important Reminders',
                'meta_description' => 'Important Reminders',
                'user_id' => 1,
                'template' => 'promo ads',
                'created_at' => '2019-10-06 20:31:26',
                'updated_at' => '2019-10-06 20:31:26'
            ]
        ];

        DB::table('pages')->insert($pages);
    }
}
