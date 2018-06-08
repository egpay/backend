<?php

namespace App\Modules\System;


class HomeSite extends SystemController{



    public function index(){
        
        $return = <<<HTML
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!--> 
<html class="no-js" lang="en"> <!--<![endif]-->
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">

		<!-- Mobile Specific Meta -->
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<!-- Always force latest IE rendering engine -->
		<meta http-equiv="X-UA-Compatible" content="IE=edge">

		<meta name="description" content="Beidea is a responsive creative agency template">
		<meta name="keywords" content="portfolio, personal, corporate, business, parallax, creative, agency">

		<!-- Title -->
		<title>EGPAY</title>

		<!-- Google fonts -->
		<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:200,300,400,500,600,700,900" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Montserrat:900" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Raleway:100,200,300,400,500,600,700" rel="stylesheet">

		<!-- Font Icon Core CSS -->
		<link rel='stylesheet' href='http://d33wubrfki0l68.cloudfront.net/bundles/79c6bed9d10f2a0c766541ee39378dd7575a9591.css'/>
		

		<!-- bootstrap css -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

		<!-- magnific-popup CSS -->
		<link rel='stylesheet' href='http://d33wubrfki0l68.cloudfront.net/css/f7c33166984ffaa7725eb2f045126d33c71e651b/css/magnific-popup.css'/>

		<!-- owl carousel CSS -->
		<link rel='stylesheet' href='http://d33wubrfki0l68.cloudfront.net/bundles/fd06a7dbf6fcde7e689595d4b6f0a76ee00250ec.css'/>
		

		<!-- Custom style CSS -->
		<link rel='stylesheet' href='/website/style.css'/>

		<!-- responsive CSS -->
		<link rel='stylesheet' href='http://d33wubrfki0l68.cloudfront.net/css/0b70682c333ffac932d6d32c7bedae4e974e6dd8/css/responsive.css'/>

		<!--[if lt IE 9]-->
		<script src='http://d33wubrfki0l68.cloudfront.net/js/1f551ad794ba616cbdeff4c3af350b88a85a559e/js/html5shiv.min.js'></script>
		<!--[endif]-->
	</head>
	<body>

		<!-- ====== Preloader ======  -->
	    <div class="loading">
			<div class="spinner">
			  <div class="rect1"></div>
			  <div class="rect2"></div>
			  <div class="rect3"></div>
			  <div class="rect4"></div>
			  <div class="rect5"></div>
			</div>
		</div>
		<!-- ======End Preloader ======  -->


		<!-- ====== Navgition ======  -->
		<nav class="navbar navbar-default">
		  <div class="container">
		    <!-- Brand and toggle get grouped for better mobile display -->
		    <div class="navbar-header">
		      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#nav-icon-collapse" aria-expanded="false">
		        <span class="sr-only">Toggle navigation</span>
		        <span class="icon-bar"></span>
		        <span class="icon-bar"></span>
		        <span class="icon-bar"></span>
		      </button>
		      <!-- logo -->
		      <a class="navbar-brand" href="http://www.egpay.com"><img src="website/logo.png" style="width: 120px;" /></a>
		    </div>

		    <!-- Collect the nav links, and other content for toggling -->
		    <div class="collapse navbar-collapse" id="nav-icon-collapse">
		      
			  <!-- links -->
		      <ul class="nav navbar-nav navbar-right">
		        <li><a href="#home" class="active">Home</a></li>
		        <li><a href="#about">About</a></li>
		        <li><a href="#contact">Contact</a></li>
		      </ul>
		    </div><!-- /.navbar-collapse -->
		  </div><!-- /.container -->
		</nav>
		<!-- ====== End Navgition ======  -->

		<!-- ====== Header ======  -->
		<section id="home" class="header demo2">
			<div class="header-overlay">
				<div class="container">
					<div class="v-middle">

						<!-- caption -->
						<div class="caption">
							<h3>Egyptian Company </h3>
							<h3 style="font-size: 30px;">
							    specialized in E-payment and E-commerce solutions through mobile application.
							</h3>
						</div>
					</div>
				</div>

				<!-- button-scroll -->
				<div class="button-scroll" data-scrollTo="about"><span></span></div>
			</div>
		</section>
		<!-- ====== End Header ======  -->

		<!-- ====== hero ======  -->
		<section id="about" class="hero section-padding">
			<div class="container">
				<div class="row">

					<!-- section head -->
					<div class="section-head text-center">
						<h4>Who We Are</h4>
						<div class="col-md-12">
							<p class="col-md-offset-2 col-md-8">
							   	Egyptian E-payment and E-commerce solutions Company, established in 2017, with a unique idea to connect E-wallet with E-commerce to enhance the Egyptian E-commerce market performance, increase merchants’ competition and customers’ benefits. 
							</p>
						</div>
						<div class="clear-fix"></div>
					</div>

				</div>
			</div>
		</section>
		<!-- ====== End hero ======  -->











		<!--====== Contact ======-->
		<section id="contact" class="contact section-padding">
			<div class="container">

				<!-- section head -->
				<div class="section-head text-center">
					<h6>Contact Us</h6>
					<h4>Get In Touch</h4>
					
					<div class="clear-fix"></div>
				</div>

				<div class="row">

					<!-- info -->
					<div class="info">
						<div class="col-sm-4">
							<div class="item">
								<div class="icon">
									<span class="icon-phone"></span>
								</div>
								<h6>Phone</h6>
								<p><a href="tel:+201101990001">+2 01101990001</a></p>
							</div>
						</div>

						<div class="col-sm-4">
							<div class="item">
								<div class="icon">
									<span class="icon-envelope"></span>
								</div>
								<h6>Mail</h6>
								<p><a href="mailto:info@egpay.com">info@egpay.com</a></p>
							</div>
						</div>
						
							<div class="col-sm-4">
							<div class="item">
								<div class="icon">
									<span class="icon-map"></span>
								</div>
								<h6>Address</h6>
								<p>29 Mohamed hassanein heikal - Nasr City</p>
							</div>
						</div>

						<div class="clear-fix"></div>
					</div>

					<!-- form -->
					<div class="main-form">
						<!-- contact form -->
						<div class="col-md-offset-2 col-md-8 col-sm-offset-1 col-sm-10">
							 <form class='form' onsubmit="sendm();return false;" id='contact-form' method='post' role='form'><input type='hidden' name='form-name' value='contact-form' />
		                        <div class="messages"></div>

		                        <div class="controls">

		                            <div class="row">
		                                <div class="col-md-6">
		                                    <div class="form-group">
		                                        <input id="form_name" type="text" name="name" placeholder="Name *" required="required" data-error="Firstname is required.">
		                                        <div class="help-block with-errors"></div>
		                                    </div>
		                                </div>

		                                 <div class="col-md-6">
		                                    <div class="form-group">
		                                        <input id="form_email" type="email" name="email" placeholder="Email *" required="required" data-error="Valid email is required.">
		                                        <div class="help-block with-errors"></div>
		                                    </div>
		                                </div>
		                            </div>
		                            <div class="row">
		                                <div class="col-md-12">
		                                    <div class="form-group">
		                                        <input id="form_subject" type="subject" name="subject" placeholder="Subject">
		                                        <div class="help-block with-errors"></div>
		                                    </div>
		                                </div>
		                            </div>
		                            <div class="row">
		                                <div class="col-md-12">
		                                    <div class="form-group">
		                                        <textarea id="form_message" name="message" placeholder="Message *" rows="4" required="required" data-error="Message."></textarea>
		                                        <div class="help-block with-errors"></div>
		                                    </div>
		                                </div>
		                                <div class="col-sm-4">
		                                    <input type="submit" class="button btn-bg-md" value="Send message">
		                                </div>
		                            </div>
		                        </div>
		                    </form>
						</div>
						<div class="clear-fix"></div>
					</div>
				</div>
			</div>
		</section>
		<!--====== End Contact ======-->

		<!--====== Footer ======-->
		<footer>
			<div class="main-footer text-center">
				<h4 class="footer-logo"><span>EGPAY </span></h4>
				<div class="social-icon">
					<a href="#0">
						<span><i class="fa fa-facebook" aria-hidden="true"></i></span>
					</a>
					<a href="#0">
						<span><i class="fa fa-twitter" aria-hidden="true"></i></span>
					</a>
					<a href="#0">
						<span><i class="fa fa-linkedin" aria-hidden="true"></i></span>
					</a>
					<a href="#0">
						<span><i class="fa fa-pinterest-p" aria-hidden="true"></i></span>
					</a>
					<a href="#0">
						<span><i class="fa fa-instagram" aria-hidden="true"></i></span>
					</a>
				</div>
			</div>
			<div class="sub-footer">
				<p>Copy Right &copy; By EGPAY 2017 | All Rights Reserved.</p>
			</div>
		</footer>
		<!--====== End Footer ======-->
		
		


		
		
		<!--====== js ======-->


		<!-- jQuery -->
		<script src="https://code.jquery.com/jquery-3.0.0.min.js"></script>
		<script src="https://code.jquery.com/jquery-migrate-3.0.0.min.js"></script>

	  	<!-- bootstrap -->
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

		<!-- singlePageNav -->
		<script src='http://d33wubrfki0l68.cloudfront.net/js/ebce443b62587282a6a7a525fd45a44cd05f49ef/layout-1/js/jquery.singlepagenav.min.js'></script>

		<!-- magnific-popup -->
		<script src='http://d33wubrfki0l68.cloudfront.net/js/fc7275f61682fa1ea733c2987b43c46eda377256/js/jquery.magnific-popup.min.js'></script>

		<!-- owl carousel -->
		<script src='http://d33wubrfki0l68.cloudfront.net/js/bb58fb45196bfdfd9264552de221742cf7d4b75a/js/owl.carousel.min.js'></script>

		<!-- stellar js -->
		<script src='http://d33wubrfki0l68.cloudfront.net/js/8fdb0d77da9a6b77397f438141d0af686517171b/js/jquery.stellar.min.js'></script>

      	<!-- isotope.pkgd.min js -->
      	<script src='http://d33wubrfki0l68.cloudfront.net/js/5ffcda38920cf4fbcff71182c20641f2058aaa11/js/isotope.pkgd.min.js'></script>

      	<!-- validator js -->
      	<script src='http://d33wubrfki0l68.cloudfront.net/js/8166dcc97a4c45dd4f117a8e9134a6b9ad1ad64a/js/validator.js'></script>

      	<!-- custom js -->
      	<script src='http://d33wubrfki0l68.cloudfront.net/js/117ecdf2f2646ee94d02cb7864b65ebfd74c870b/js/custom.js'></script>
        <script>
            function sendm(){
                
            }
            
        </script>
	</body>
</html>
HTML;
    
    
    return $return;

    }


}