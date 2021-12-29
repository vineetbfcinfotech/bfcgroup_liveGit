<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!-->
<?php 
if (!empty($_POST["submit"])) {
$name =    $_POST["submit"];
$phone =    $_POST["phone"];
$message =    $_POST["message"];
$to = "aaadisy@gmail.com";
$subject = "Lead From Website";
$message = '<html><body>';
$message .= '<h1 style="color:#f40;">Hi Team!</h1>
<p>New Lead Submitted on Website Here are The details</p>';
$message .= '<p style="color:#080;font-size:18px;">Name : '.$name .'</p>';
$message .= '<p style="color:#080;font-size:18px;">Phone : '.$phone .'</p>';
$message .= '<p style="color:#080;font-size:18px;">Message : '.$message .'</p>';
$message .= '</body></html>';

$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
if(mail($to, $subject, $message, $headers)){
    echo '<script language="javascript">';
echo 'alert("Message successfully sent")';
echo '</script>';
} else{
    
     echo '<script language="javascript">';
echo 'alert("Unable to send Message. Please try again")';
echo '</script>';
}
}


?>
<html class="no-js">
<!--<![endif]-->


<head>
	
	<title>Welcome to Future Utility Consultants</title>
	<meta charset="utf-8">
	<!--[if IE]>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<![endif]-->
	<meta name="description" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="format-detection" content="telephone=no">

	<!-- Place favicon.ico and apple-touch-icon.png in the root directory -->

	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/animations.css">
	<link rel="stylesheet" href="css/font-awesome5.css">
	<link rel="stylesheet" href="css/icomoon.css">
	<link rel="stylesheet" href="css/main.css" class="color-switcher-link">
	<link rel="stylesheet" href="css/shop.css" class="color-switcher-link">
	<script src="js/vendor/modernizr-2.6.2.min.js"></script>

	<!--[if lt IE 9]>
		<script src="js/vendor/html5shiv.min.js"></script>
		<script src="js/vendor/respond.min.js"></script>
		<script src="js/vendor/jquery-1.12.4.min.js"></script>
	<![endif]-->

</head>

<body>
	<!--[if lt IE 9]>
		<div class="bg-danger text-center">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/" class="color-main">upgrade your browser</a> to improve your experience.</div>
	<![endif]-->

	<div class="preloader">
		<div class="preloader_image"></div>
	</div>

	<!-- search modal -->
	<div class="modal" tabindex="-1" role="dialog" aria-labelledby="search_modal" id="search_modal">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
		<div class="widget widget_search">
			<form method="get" class="searchform search-form" action="http://webdesign-finder.com/">
				<div class="form-group">
					<input type="text" value="" name="search" class="form-control" placeholder="Search keyword" id="modal-search-input">
				</div>
				<button type="submit" class="btn">Search</button>
			</form>
		</div>
	</div>
	<div id="team-form" class="ds modal">
		<div class="container">
			<div class="row">
				<div class="col-md-6 offset-md-3">
					<div class="form-wrapper ls rounded">
						<form class="contact-form" method="post" action="">
							<div class="row c-mb-20">
								<div class="col-12 form-title text-center form-builder-item">
									<div class="header title">
										<h4><span class="thin">Contact</span> me</h4>
									</div>
								</div>
							</div>
							<div class="row c-mb-20">
								<div class="col-sm-12">
									<div class="form-group has-placeholder">
										<label for="name333">Full Name <span class="required">*</span></label>
										<input type="text" aria-required="true" size="30" value="" name="name" id="name333" class="form-control" placeholder="Your name">
									</div>
								</div>
								<div class="col-sm-12">
									<div class="form-group has-placeholder">
										<label for="phone35533">Phone<span class="required">*</span></label>
										<input type="tel" aria-required="true" size="30" name="phone" id="phone35533" class="form-control" placeholder="Phone Number">
									</div>
								</div>
							</div>
							<div class="row c-mb-20">
								<div class="col-sm-12">
									<div class="form-group has-placeholder">
										<label for="message333">Message</label>
										<textarea aria-required="true" rows="3" cols="45" name="message" id="message333" class="form-control" placeholder="Message"></textarea>
									</div>
								</div>
							</div>
							<div class="row c-mb-20">
								<div class="col-sm-12 mb-0 mt-10 text-center">
									<div class="form-group">
										<input class="btn btn-gradient" type="submit" value="Contact me">
									</div>
								</div>
							</div>
						</form>
					</div>

				</div>
			</div>


		</div>


	</div>


	<!-- Unyson messages modal -->
	<div class="modal fade" tabindex="-1" role="dialog" id="messages_modal">
		<div class="fw-messages-wrap ls p-normal">
			<!-- Uncomment this UL with LI to show messages in modal popup to your user: -->
			<!--
		<ul class="list-unstyled">
			<li>Message To User</li>
		</ul>
		-->

		</div>
	</div><!-- eof .modal -->

	<!-- wrappers for visual page editor and boxed version of template -->
	<div id="canvas">
		<div id="box_wrapper">

			<!-- template sections -->

			<!--topline section visible only on small screens|-->

			<div class="header_absolute">
				<!--eof topline-->
				<!-- header with two Bootstrap columns - left for logo and right for navigation -->
				<header class="page_header_side header_slide header-special header_side_right ds">

					<div class="scrollbar-macosx">
						<div class="side_header_inner">
							<p class="text-right mb-0 close-wrapper"><a href="javascript:void(0)">×</a></p>

							<div class="widget widget_recent_posts">

								<h3 class="widget-title">Our news</h3>
								<ul class="list-unstyled">
									<li class="media">
										<article class="vertical-item  post type-post status-publish format-standard has-post-thumbnail">
											<div class="item-content">
												<div class="entry-header">
													<div class="entry-meta">
														<div class="byline">
															<span class="date">
																<a href="blog-%40%40type.html" rel="bookmark">
																	<time class="published entry-date" datetime="2019-04-09T12:23:09+00:00">20.03.2019</time>
																</a>
															</span>
															<span class="author vcard">
																<a class="url fn n" href="blog-%40%40type.html" rel="author"><span>by</span> Admin</a>
															</span>
														</div>
													</div>
													<h4 class="entry-title">
														<a href="blog-single-%40%40type.html" rel="bookmark">
															Adipisicing elit sed do eiusmod
														</a>
													</h4>

													<!-- .entry-meta -->
												</div>
												<!-- .entry-header -->
											</div><!-- .item-content -->
										</article>
									</li>
									<li class="media">
										<article class="vertical-item  post type-post status-publish format-standard has-post-thumbnail">
											<div class="item-content">
												<div class="entry-header">
													<div class="entry-meta">
														<div class="byline">
															<span class="date">
																<a href="blog-%40%40type.html" rel="bookmark">
																	<time class="published entry-date" datetime="2019-04-09T12:23:09+00:00">20.03.2019</time>
																</a>
															</span>
															<span class="author vcard">
																<a class="url fn n" href="blog-%40%40type.html" rel="author"><span>by</span> Admin</a>
															</span>
														</div>
													</div>
													<h4 class="entry-title">
														<a href="blog-single-%40%40type.html" rel="bookmark">
															Adipisicing elit sed do eiusmod
														</a>
													</h4>

													<!-- .entry-meta -->
												</div>
												<!-- .entry-header -->
											</div><!-- .item-content -->
										</article>
									</li>
									<li class="media">
										<article class="vertical-item  post type-post status-publish format-standard has-post-thumbnail">
											<div class="item-content">
												<div class="entry-header">
													<div class="entry-meta">
														<div class="byline">
															<span class="date">
																<a href="blog-%40%40type.html" rel="bookmark">
																	<time class="published entry-date" datetime="2019-04-09T12:23:09+00:00">20.03.2019</time>
																</a>
															</span>
															<span class="author vcard">
																<a class="url fn n" href="blog-%40%40type.html" rel="author"><span>by</span> Admin</a>
															</span>
														</div>
													</div>
													<h4 class="entry-title">
														<a href="blog-single-%40%40type.html" rel="bookmark">
															Adipisicing elit sed do eiusmod
														</a>
													</h4>

													<!-- .entry-meta -->
												</div>
												<!-- .entry-header -->
											</div><!-- .item-content -->
										</article>
									</li>

								</ul>
							</div>
							<div class="widget widget_about">
								<h3 class="widget-title">About</h3>
								<p>
									Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Sed ut perspiciatis unde.
								</p>
							</div>
							<div class="widget widget_mailchimp">
								<h3 class="widget-title">Newsletter</h3>

								<form class="signup" action="http://webdesign-finder.com/">
									<label for="mailchimp_email88d">
										<span class="screen-reader-text">Subscribe:</span>
									</label>
									<input id="mailchimp_email88d" name="email" type="email" class="form-control mailchimp_email has-placeholder" placeholder="Email">
									<button type="submit" class="search-submit">
										<span class="screen-reader-text">Subscribe</span>
									</button>
									<div class="response"></div>
								</form>
							</div>
							<div class="widget widget_social_icons">
								<a href="#" class="fab fa-facebook-f rounded-icon bg-icon fs-16" title="facebook"></a>
								<a href="#" class="fab fa-twitter rounded-icon bg-icon fs-16" title="telegram"></a>
								<a href="#" class="fab fa-linkedin-in rounded-icon bg-icon fs-16" title="linkedin"></a>
								<a href="#" class="fab fa-instagram rounded-icon bg-icon fs-16" title="instagram"></a>
							</div>
						</div>
					</div>
				</header>

				<header class="page_header header-1 ds bg-transparent s-py-xl-20 s-py-10 ">

					<div class="container-fluid">

						<div class="row d-flex align-items-center justify-content-center">
							<div class="col-xl-3 col-md-4 col-12 text-center">
								<a href="index.html" class="logo">
									<img src="images/logo.png" alt="">
									<span class="d-flex flex-column">
										<span class="logo-text color-darkgrey">Future  </span>
										<span class="logo-subtext">Utility Consultants</span>
									</span>
								</a>
							</div>
							<div class="col-xl-6 col-1 text-right">
								<!-- main nav start -->
								<nav class="top-nav">
									<ul class="nav sf-menu">


										<li class="active">
											<a href="index-2.html">Home</a>
											
										</li>

										<li>
											<a href="about.html">About</a>
											
										</li>
										<!-- eof pages -->


										<!--<li>
											<a href="#">Features</a>
											<div class="mega-menu">
												<ul class="mega-menu-row">
													<li class="mega-menu-col">
														<a href="#">Headers</a>
														<ul>
															<li>
																<a href="header1.html">Header Type 1</a>
															</li>
															<li>
																<a href="header2.html">Header Type 2</a>
															</li>
															<li>
																<a href="header3.html">Header Type 3</a>
															</li>
															<li>
																<a href="header4.html">Header Type 4</a>
															</li>
															<li>
																<a href="header5.html">Header Type 5</a>
															</li>
															<li>
																<a href="header6.html">Header Type 6</a>
															</li>
														</ul>
													</li>
													<li class="mega-menu-col">
														<a href="#">Side Menus</a>
														<ul>
															<li>
																<a href="header-side.html">Push Left</a>
															</li>
															<li>
																<a href="header-side-right.html">Push Right</a>
															</li>
															<li>
																<a href="header-side-slide.html">Slide Left</a>
															</li>
															<li>
																<a href="header-side-slide-right.html">Slide Right</a>
															</li>
															<li>
																<a href="header-side-sticked.html">Sticked Left</a>
															</li>
															<li>
																<a href="header-side-sticked-right.html">Sticked Right</a>
															</li>
														</ul>
													</li>
													<li class="mega-menu-col">
														<a href="title1.html">Title Sections</a>
														<ul>
															<li>
																<a href="title1.html">Title section 1</a>
															</li>
															<li>
																<a href="title2.html">Title section 2</a>
															</li>
															<li>
																<a href="title3.html">Title section 3</a>
															</li>
															<li>
																<a href="title4.html">Title section 4</a>
															</li>
															<li>
																<a href="title5.html">Title section 5</a>
															</li>
															<li>
																<a href="title6.html">Title section 6</a>
															</li>
														</ul>
													</li>
													<li class="mega-menu-col">
														<a href="footer1.html#footer">Footers</a>
														<ul>
															<li>
																<a href="footer1.html#footer">Footer Type 1</a>
															</li>
															<li>
																<a href="footer2.html#footer">Footer Type 2</a>
															</li>
															<li>
																<a href="footer3.html#footer">Footer Type 3</a>
															</li>
															<li>
																<a href="footer4.html#footer">Footer Type 4</a>
															</li>
															<li>
																<a href="footer5.html#footer">Footer Type 5</a>
															</li>
															<li>
																<a href="footer6.html#footer">Footer Type 6</a>
															</li>
														</ul>
													</li>
													<li class="mega-menu-col">
														<a href="copyright1.html#copyright">Copyright</a>

														<ul>
															<li>
																<a href="copyright1.html#copyright">Copyright 1</a>
															</li>
															<li>
																<a href="copyright2.html#copyright">Copyright 2</a>
															</li>
															<li>
																<a href="copyright3.html#copyright">Copyright 3</a>
															</li>
															<li>
																<a href="copyright4.html#copyright">Copyright 4</a>
															</li>
															<li>
																<a href="copyright5.html#copyright">Copyright 5</a>
															</li>
															<li>
																<a href="copyright6.html#copyright">Copyright 6</a>
															</li>
														</ul>
													</li>

												</ul>
											</div> 
										</li>-->
										<!-- eof features -->


										<!-- blog -->
										<li>
											<a href="#">Business Utilities</a>
											<ul>

												<li>
													<a href="gas_quote.html">Gas Quote</a>
												</li>
												<li>
													<a href="electricity_quote.html">Electricity Quote</a>
												</li>
												<li>
													<a href="water_saving.html">Water Saving</a>
												</li>
												<li>
													<a href="telecom_services.html">Telecom Services</a>
												</li>


												


											</ul>
										</li>
										<!-- eof blog -->
										
										
										<li>
											<a href="#">Our Suppliers </a>
											
										</li>

										<!-- shop -->
										
										<!-- eof shop -->

										<!-- contacts -->
										<li>
											<a href="contact.html">Contact</a>
											
										</li>
										
										<!-- Modal -->
<div class="modal fade" id="darkModalForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
  aria-hidden="true">
  <div class="modal-dialog form-dark" role="document">
    <!--Content-->
    <div class="modal-content card card-image" style="background-color: black; url('https://mdbootstrap.com/img/Photos/Others/pricing-table%20(7).jpg');">
      <div class="text-white rgba-stylish-strong py-5 px-5 z-depth-4">
        <!--Header-->
        <div class="modal-header text-center pb-4">
          <h3 class="modal-title w-100 white-text font-weight-bold" id="myModalLabel"><strong>Register Form </strong> <a
              class="green-text font-weight-bold"><strong> </strong></a></h3>
          <button type="button" class="close white-text" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <!--Body-->
        <div class="modal-body">
          <!--Body-->
          <div class="md-form mb-2">
            <input type="email" id="Form-email5" class="form-control validate white-text">
            <label data-error="wrong" data-success="right" for="Form-email5">Your Name</label>
          </div>
			
			<div class="md-form mb-2">
            <input type="email" id="Form-email5" class="form-control validate white-text">
            <label data-error="wrong" data-success="right" for="Form-email5">Email Id</label>
          </div>

          <div class="md-form pb-2">
            <input type="password" id="Form-pass5" class="form-control validate white-text">
            <label data-error="wrong" data-success="right" for="Form-pass5">Phone Number</label>
            <div class="form-group mt-2">
              <input class="form-check-input" type="checkbox" id="checkbox624">
              <!--<label for="checkbox624" class="white-text form-check-label">Accept the<a href="#" class="green-text font-weight-bold">
                  Terms and Conditions</a></label>-->
            </div>
          </div>

          <!--Grid row-->
          <div class="row d-flex align-items-center mb-2">

            <!--Grid column-->
            <div class="text-center mb-2 col-md-12">
              <button type="button" class="btn btn-success btn-block btn-rounded z-depth-1">Send</button>
            </div>
            <!--Grid column-->

          </div>
          <!--Grid row-->

          <!--Grid row-->
          <div class="row">

            <!--Grid column-->
            <!--<div class="col-md-12">
              <p class="font-small white-text d-flex justify-content-end">Have an account? <a href="#" class="green-text ml-1 font-weight-bold">
                  Log in</a></p>
            </div>-->
            <!--Grid column-->

          </div>
          <!--Grid row-->

        </div>
      </div>
    </div>
    <!--/.Content-->
  </div>
</div>
<!-- Modal -->

<!--<div class="text-center">
  <a href="" class="btn btn-default btn-rounded" data-toggle="modal" data-target="#darkModalForm">Register Form</a>
</div>-->
										<!-- eof contacts -->
									</ul>


								</nav>
								<!-- eof main nav -->
							</div>
							
							<div class="col-xl-3 col-md-7 col-12  d-flex justify-content-md-end">
								<ul class="top-includes">
									<li class="metaphone">
										<a href="#">Call-0141 536 0378</a>
									</li>
									<li class="special-menu">
										<span class="toggle_menu toggle_menu_side header-slide toggle_menu_side_special"><span></span></span>
									</li>
								</ul>


							</div>

						</div>

					</div>
					<!-- header toggler -->
					<span class="toggle_menu"><span>menu</span></span>
				</header>
			</div>

			<section class="page_slider">
				<div class="flexslider">
					<ul class="slides">
						<li class="ds cover-image">
							<img src="images/slide01.jpg" alt="img">
							<div class="container-fluid">
								<div class="row">
									<div class="col-md-12">
										<div class="intro_layers_wrapper">
											<div class="intro_layers">
												<div class="intro_layer">
													<!--<h6 class="intro_before_featured_word">01. Providing for Today</h6>-->
													<h2 class="text-capitalize intro_featured_word">
														Welcome to
														Future Utility Consultants
													</h2>
													
												
													<a href="" class="btn btn-outline-darkgrey big-btn" data-toggle="modal" data-target="#darkModalForm">get a quote</a>
													<!--<span class="text-divider">or</span>
													<a href="#" class="btn just-link">Request a Callback</a>-->
												</div>
											</div> <!-- eof .intro_layers -->
										</div> <!-- eof .intro_layers_wrapper -->
									</div> <!-- eof .col-* -->
								</div><!-- eof .row -->
							</div><!-- eof .container-fluid -->
						</li>
						<li class="ds cover-image">
							<img src="images/slide02.jpg" alt="img">
							<div class="container-fluid">
								<div class="row">
									<div class="col-md-12">
										<div class="intro_layers_wrapper">
											<div class="intro_layers">
												<div class="intro_layer">
													<!--<h6 class="intro_before_featured_word">02. Innovations</h6>-->
													<h2 class="text-capitalize intro_featured_word">
														Welcome to 
														Future Utility Consultants
													</h2>
													<a href="" class="btn btn-outline-darkgrey big-btn" data-toggle="modal" data-target="#darkModalForm">get a quote</a>
													<!--<span class="text-divider">or</span>
													<a href="#" class="btn just-link">Request a Callback</a>-->
												</div>
											</div> <!-- eof .intro_layers -->
										</div> <!-- eof .intro_layers_wrapper -->
									</div> <!-- eof .col-* -->
								</div><!-- eof .row -->
							</div><!-- eof .container-fluid -->
						</li>
						<li class="ds cover-image">
							<img src="images/slide03.jpg" alt="img">
							<div class="container-fluid">
								<div class="row">
									<div class="col-md-12">
										<div class="intro_layers_wrapper">
											<div class="intro_layers">
												<div class="intro_layer">
													<!--<h6 class="intro_before_featured_word">03. Industry</h6>-->
													<h2 class="text-capitalize intro_featured_word">
														Welcome to
														Future Utility Consultants
													</h2>
													<a href="" class="btn btn-outline-darkgrey big-btn" data-toggle="modal" data-target="#darkModalForm">get a quote</a>
													<!--<span class="text-divider">or</span>
													<a href="#" class="btn just-link">Request a Callback</a>-->
												</div>
											</div> <!-- eof .intro_layers -->
										</div> <!-- eof .intro_layers_wrapper -->
									</div> <!-- eof .col-* -->
								</div><!-- eof .row -->
							</div><!-- eof .container-fluid -->
						</li>


					</ul>
				</div> <!-- eof flexslider -->
			</section>

			<section class="ds text-sm-left text-center container-px-0 c-gutter-0">
				<div class="container-fluid">
					<div class="row service-v2">
						<div class="col-sm-6 col-md-4 col-xl-6   ">
							<div class="icon-box service-single with-icon layout2 ds text-center">
								<a class="link-icon" href="service-single.html">
									<div class="icon-styled  fs-50">
										<i class="ico ico-refinery"></i>
									</div>
								</a>
								<a href="service-single.html">
									<h5>
										Electricity Quote
									</h5>
								</a>

								<p>We understand that no matter how your business or industry changes over the next few years...</p>
								<a class="btn btn-outline-darkgrey" href="#">
									<i class="fas fa-chevron-right"></i>
								</a>
							</div>
						</div>
						
						<div class="col-sm-6 col-md-4 col-xl-6   ">
							<div class="icon-box service-single with-icon layout2 ds text-center">
								<a class="link-icon" href="service-single.html">
									<div class="icon-styled  fs-50">
										<i class="ico ico-oil"></i>
									</div>
								</a>
								<a href="service-single.html">
									<h5>
										Gas Quote
									</h5>
								</a>

								<p>Business Gas prices in the UK have increased by almost 200 % in the last 10 years. With an increasing</p>
								<a class="btn btn-outline-darkgrey" href="#">
									<i class="fas fa-chevron-right"></i>
								</a>
							</div>
						</div>
						
						
						
					</div>
				</div>
			</section>

			<section class="ls main-testimonial s-py-xl-160 s-py-lg-130 s-py-md-90 s-py-60">
				<div class="container">
					<div class="row">
						<div class="col-12 col-lg-3 text-center">
							<h2 class="special-heading text-center">
								<span class="text-capitalize">
									welcome!
								</span>
							</h2>
							<div class="divider-30"></div>
							<a class="btn btn-gradient big-btn" href="#">get a quote</a>
						</div>
						<div class="col-12 col-lg-9 text-center">
							<div class="divider-35 hidden-above-lg"></div>
							<div class="divider--5"></div>
							<p class="excerpt">
								Providing a complete solution for your business [email protected]
							</p>
							<p>
								We strive to provide Our Customers with Top Notch Support to make their Experience Wonderful

							</p>
						</div>
						<!--<div class="col-12 col-lg-3 text-sm-left text-center">
							<div class="divider-35 hidden-above-lg"></div>
							<div class="signature">
								<div class="signature-image">
									<img src="images/team/testimonial2.jpg" alt="">
								</div>
								<div class="signature-content">
									<span>Diana T. Davis</span>
									<img src="images/signature.png" alt="">
								</div>
							</div>
						</div>-->
					</div>
				</div>
			</section>
			
			
			<div class="container">
					<div class="row">
						<div class="col-12">
							<div class="row isotope-wrapper masonry-layout c-gutter-30 c-mb-30" style="position: relative; height: 1073.6px;">
								<div class="col-xl-3 col-md-6  " style="position: absolute; left: 0%; top: 0px;">
									<div class="icon-box service-single with-icon rounded  ls big-padding text-center">
										<a class="link-icon" href="service-single.html">
											<div class="icon-styled bg-icon grey-bg color-darkgrey round fs-30">
												<i class="ico ico-pipe"></i>
											</div>
										</a>
										<a href="#">
											<h5 class="">
												OUR WATER SOLUTION
											</h5>
										</a>

										<p>A complete water solution for your business, from SMEs to large organisations, single site or multi-site.</p>
										<a class="btn btn-outline-maincolor" href="#">
											read more
										</a>
									</div>
								</div>
								<div class="col-xl-3 col-md-6  " style="position: absolute; left: 33.3333%; top: 0px;">
									<div class="icon-box service-single with-icon rounded  ls big-padding text-center">
										<a class="link-icon" href="service-single.html">
											<div class="icon-styled bg-icon grey-bg color-darkgrey round fs-30">
												<i class="ico ico-oil"></i>
											</div>
										</a>
										<a href="#">
											<h5 class="">
												WATER DEREGULATION
											</h5>
										</a>

										<p>Under new legislation businesses can switch their water and wastewater provider.</p>
										<a class="btn btn-outline-maincolor" href="#">
											read more
										</a>
									</div>
								</div>
								<div class="col-xl-3 col-md-6  " style="position: absolute; left: 66.6667%; top: 0px;">
									<div class="icon-box service-single with-icon rounded  ls big-padding text-center">
										<a class="link-icon" href="service-single.html">
											<div class="icon-styled bg-icon grey-bg color-darkgrey round fs-30">
												<i class="ico ico-electric-factory"></i>
											</div>
										</a>
										<a href="#">
											<h5 class="">
												WATER METERING
											</h5>
										</a>

										<p>Manage all water metering requirements for your business.  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <br> &nbsp; <br> &nbsp; </p>
										<a class="btn btn-outline-maincolor" href="#">
											read more
										</a>
									</div>
								</div>
								<div class="col-xl-3 col-md-6  " style="position: absolute; left: 0%; top: 536.8px;">
									<div class="icon-box service-single with-icon rounded  ls big-padding text-center">
										<a class="link-icon" href="service-single.html">
											<div class="icon-styled bg-icon grey-bg color-darkgrey round fs-30">
												<i class="ico ico-power-plant"></i>
											</div>
										</a>
										<a href="#">
											<h5 class="">
												WATER SUPPLIERS
											</h5>
										</a>

										<p>Businesses will be able to choose from among more than 20 suppliers. &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <br> &nbsp; <br> &nbsp; </p>
										<a class="btn btn-outline-maincolor" href="#">
											read more
										</a>
									</div>
								</div>
								
								
							</div>
						</div>
					</div>
					<div class="mt--30"></div>
				</div>

			<section class="ls  s-py-xl-160 s-py-lg-130 s-py-md-90 s-py-60 text-sm-left text-center container-px-0">
				<div class="cover-image s-cover-left">
					<img src="images/services/service1.jpg" alt="01">
				</div>
				<div class="container-fluid">
					<div class="row">
						<div class="col-xs-12 col-12 col-lg-6">

						</div>
						<div class="col-xs-12 col-12 col-lg-6">
							<div class="content-center">
								<h2 class="special-heading numeric text-sm-left text-center">
									<span class="text-capitalize">
										WATER IS OPEN FOR BUSINESS
									</span>
								</h2>
								<div class="divider-45 hidden-below-lg"></div>
								<div class="divider-30 hidden-above-lg"></div>
								<p>
									With the recent changes to the water market, which came in to effect on 1 April 2017, most businesses and organisations in England can now choose which company they want to supply their retail water services.
								</p>
								<div class="divider-45 hidden-below-lg"></div>
								<div class="divider-30 hidden-above-lg"></div>
								<ul class="list-styled">
									<li>better value for your money</li>
									<li>lowering your bills and charges</li>
									<li>improving the customer service you get</li>
									<li>tailored services for your company</li>
									<li>help to become a more water efficient business</li>
								</ul>
								<div class="divider--10"></div>
							</div>
						</div>
					</div>
				</div>
			</section>

			

			<!--clint Logo-->

			

			<section class="ls  s-py-xl-160 s-py-lg-130 s-py-md-90 s-py-60 text-sm-left text-center c-gutter-60">
				<div class="container">
					<div class="row">
						
						
						
						<div class="col-12 col-lg-12">
							<div class="divider-30 hidden-above-lg"></div>
							<div class="row isotope-wrapper masonry-layout images-grid c-mb-30 c-gutter-30">
								
								<div class="col-lg-12 col-sm-12  ">
									<a href="#">
										<div class="bordered text-center p-xl-50 p-20 rounded">
											<img src="images/partners/partneer_logo.png" alt="">
										</div>
									</a>
								</div>
								
							</div>
							<!--<div class="mt--30"></div>-->
						</div>
						
						
						
						<!--<div class="col-12 col-lg-12">
							<div class="divider-30 hidden-above-lg"></div>
							<div class="row isotope-wrapper masonry-layout images-grid c-mb-30 c-gutter-30">
								
								<div class="col-lg-2 col-sm-6  ">
									<a href="#">
										<div class="bordered text-center p-xl-50 p-20 rounded">
											<img src="images/partners/1.png" alt="">
										</div>
									</a>
								</div>
								<div class="col-lg-2 col-sm-6  ">
									<a href="#">
										<div class="bordered text-center p-xl-50 p-20 rounded">
											<img src="images/partners/2.png" alt="">
										</div>
									</a>
								</div>
								<div class="col-lg-2 col-sm-6  ">
									<a href="#">
										<div class="bordered text-center p-xl-50 p-20 rounded">
											<img src="images/partners/3.png" alt="">
										</div>
									</a>
								</div>
								<div class="col-lg-2 col-sm-6  ">
									<a href="#">
										<div class="bordered text-center p-xl-50 p-20 rounded">
											<img src="images/partners/4.png" alt="">
										</div>
									</a>
								</div>
								<div class="col-lg-2 col-sm-6  ">
									<a href="#">
										<div class="bordered text-center p-xl-50 p-20 rounded">
											<img src="images/partners/5.png" alt="">
										</div>
									</a>
								</div>
								<div class="col-lg-2 col-sm-6  ">
									<a href="#">
										<div class="bordered text-center p-xl-50 p-20 rounded">
											<img src="images/partners/6.png" alt="">
										</div>
									</a>
								</div>
								<div class="col-lg-2 col-sm-6  ">
									<a href="#">
										<div class="bordered text-center p-xl-50 p-20 rounded">
											<img src="images/partners/7.png" alt="">
										</div>
									</a>
								</div>
								<div class="col-lg-2 col-sm-6  ">
									<a href="#">
										<div class="bordered text-center p-xl-50 p-20 rounded">
											<img src="images/partners/8.png" alt="">
										</div>
									</a>
								</div>
								<div class="col-lg-2 col-sm-6  ">
									<a href="#">
										<div class="bordered text-center p-xl-50 p-20 rounded">
											<img src="images/partners/9.png" alt="">
										</div>
									</a>
								</div>
								<div class="col-lg-2 col-sm-6  ">
									<a href="#">
										<div class="bordered text-center p-xl-50 p-20 rounded">
											<img src="images/partners/10.png" alt="">
										</div>
									</a>
								</div>
								<div class="col-lg-2 col-sm-6  ">
									<a href="#">
										<div class="bordered text-center p-xl-50 p-20 rounded">
											<img src="images/partners/10.png" alt="">
										</div>
									</a>
								</div>
								<div class="col-lg-2 col-sm-6  ">
									<a href="#">
										<div class="bordered text-center p-xl-50 p-20 rounded">
											<img src="images/partners/10.png" alt="">
										</div>
									</a>
								</div>
								
							</div>
							<div class="mt--30"></div>
						</div>-->
					</div>
				</div>
			</section>
			
			
			
			<!--End Clint Logo-->

			

			<section class="ds call-to-action text-center  s-py-xl-160 s-py-lg-130 s-py-md-90 s-py-60">
				<div class="container">
					<div class="row">
						<div class="col-12">
							<h2 class="special-heading text-center">
								<span class="text-capitalize big">
									More About Future Utility Consultants 
								</span>
							</h2>
							<div class="divider-45 hidden-below-lg"></div>
							<div class="divider-30 hidden-above-lg"></div>
							<a class="btn btn-darkgrey big-btn" href="#">Check Out</a>
						</div>
					</div>
				</div>
			</section>

			

			


			<footer class="page_footer  text-center c-gutter-100 text-sm-left  ds">
				<div class="container">
					<div class="row justify-content-center">

						<div class="col-lg-4 col-md-6 order-1 order-lg-1  animate" data-animation="fadeInUp">
							<div class="fw-divider-space divider-xl-160 divider-lg-130 divider-md-90 divider-60"></div>
							<a href="index.html" class="logo">
								<img src="images/logo.png" alt="">
								<span class="d-flex flex-column">
									<span class="logo-text color-darkgrey">Future</span>
									<span class="logo-subtext">Utility Consultants</span>
								</span>
							</a>
							<p>
								Eligible businesses, charities and public sector customers are no longer restricted to buying retail water services from their regional water company. Instead, they are now free to choose their water retailer.
							</p>
							<a href="#" class="fab fa-facebook-f rounded-icon bg-icon fs-16" title="facebook"></a>
							<a href="#" class="fab fa-twitter rounded-icon bg-icon fs-16" title="telegram"></a>
							<a href="#" class="fab fa-linkedin-in rounded-icon bg-icon fs-16" title="linkedin"></a>
							<a href="#" class="fab fa-instagram rounded-icon bg-icon fs-16" title="instagram"></a>
							<div class="fw-divider-space divider-xl-160 divider-lg-130 divider-md-60 divider-30"></div>
						</div>

						<div class="col-lg-4 col-md-12 ls order-3 order-lg-2 footer-special-column text-center animate" data-animation="fadeInUp">
							<div class="form-wrapper">
								<form class="contact-form" method="post" action="http://webdesign-finder.com/">
									<div class="row c-mb-20">
										<div class="col-12 form-title text-center form-builder-item">
											<div class="header title">
												<h2>Write Us</h2>
											</div>
										</div>
									</div>
									<div class="row c-mb-10 c-gutter-10">
										<div class="col-lg-12 text-center">
											<div class="form-group has-placeholder">
												<label for="name22335x5553">Full Name <span class="required">*</span></label>
												<input type="text" aria-required="true" size="30" value="" name="name" id="name22335x5553" class="form-control" placeholder="Full Name">
											</div>
										</div>
									</div>
									<div class="row c-mb-10 c-gutter-10">
										<div class="col-lg-12 text-center">
											<div class="form-group has-placeholder">
												<label for="name223355553">Phone Number <span class="required">*</span></label>

												<input type="text" aria-required="true" size="30" value="" name="name" id="name223355553" class="form-control" placeholder="Phone Number">
											</div>
										</div>
									</div>
									<div class="row c-mb-10 c-gutter-10">
										<div class="col-sm-12 text-center">
											<div class="form-group has-placeholder">
												<label for="message22335553">Message</label>

												<textarea aria-required="true" rows="6" cols="45" name="message" id="message22335553" class="form-control" placeholder="Your Message"></textarea>
											</div>
										</div>
									</div>
									<div class="row c-mb-10 c-gutter-10">
										<div class="col-sm-12 mb-0 mt-50">
											<div class="form-group">
												<input class="btn btn-gradient big-btn" type="submit" value="Send message">
											</div>
										</div>
									</div>
								</form>
							</div>
							<!--<h6 class="fs-12 text-uppercase">&copy; Copyright <span class="copyright_year">2019</span> All Rights Reserved</h6>-->
						</div>

						<div class="col-lg-4 col-md-6 order-2 order-lg-3 animate" data-animation="fadeInUp">
							<div class="fw-divider-space divider-xl-160 divider-lg-130 divider-md-90"></div>
							<div class="widget widget_icons_list">
								<ul>
									<li class="icon-inline ">
										<div class="icon-styled icon-top  bordered round fs-16">
											<i class="fas fa-phone"></i>
										</div>
										<p>0141 536 0378</p>
									</li>
									<li class="icon-inline">
										<div class="icon-styled icon-top bordered round  fs-16">
											<i class="fas fa-envelope"></i>


										</div>
										<p><a href="#">info@futureutilityconsultants.com</a></p>
									</li>
									<li class="icon-inline">
										<div class="icon-styled icon-top bordered round  fs-16">
											<i class="fas fa-map-marker-alt"></i>
										</div>
										<p>
											60 St. Enoch Square, Glasgow, G1 4AG

										</p>
									</li>
									<li class="icon-inline">
										<div class="icon-styled icon-top bordered round  fs-16">
											<i class="fas fa-clock"></i>
										</div>
										<p>
											Mo-Fri: 8am - 6pm<br>
											Sat: 10am - 4pm<br>
											Sun: of
										</p>
									</li>
								</ul>
							</div>
							<div class="fw-divider-space divider-xl-160 divider-lg-130 divider-md-60 divider-30"></div>
						</div>
					</div>
				</div>

			</footer>


		</div><!-- eof #box_wrapper -->
	</div><!-- eof #canvas -->

<div id="enquirypopup" class="modal fade in" role="dialog">
<div class="modal-dialog">

<!-- Modal content-->
<div class="modal-content row">
<div class="modal-header custom-modal-header">
<button type="button" class="close" data-dismiss="modal">×</button>
<h4 class="modal-title">Enquire Now</h4>
</div>
<div class="modal-body">
<form name="info_form" class="form-inline" action="#" method="post">
<div class="form-group col-sm-12">
<input type="text" class="form-control" name="name" id="name" placeholder="Enter Name">
</div>
<div class="form-group col-sm-12">
<input type="email" class="form-control" name="email" id="email" placeholder="Enter Email">
</div>
<div class="form-group col-sm-12">
<input type="text" class="form-control" name="checkin" id="cheeckin" placeholder="Check-In Date">
</div>
<div class="form-group col-sm-12">
<input type="text" class="form-control" name="checkout" id="cheeckout" placeholder="Check-Out Date">
</div>
<div class="form-group col-sm-12">
<input type="text" class="form-control" name="phone" id="phone" placeholder="Enter Phone">
</div>

<div class="form-group col-sm-12">
<textarea class="form-control" id="msg" name="msg" rows="4" placeholder="Enter Message"></textarea>
</div>
<div class="form-group col-sm-12">
<button type="submit" class="btn btn-default pull-right">Submit</button>
</div>
</form>
</div>

</div>

</div>
</div>








	<script src="js/compressed.js"></script>
	<script src="js/main.js"></script>
	<script src="js/switcher.js"></script>

	<!-- Google Map Script -->
	<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC0pr5xCHpaTGv12l73IExOHDJisBP2FK4&amp;callback=initGoogleMap"></script>

	
	<script>
    $(window).load(function(){
        setTimeout(function() {
                $('#enquirypopup').modal('show');
        }, 3000);
            });

</script>
	
	<!-- Start of LiveChat (www.livechatinc.com) code -->
<script type="text/javascript">
  window.__lc = window.__lc || {};
  window.__lc.license = 11412593;
  (function() {
    var lc = document.createElement('script'); lc.type = 'text/javascript'; lc.async = true;
    lc.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'cdn.livechatinc.com/tracking.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(lc, s);
  })();
</script>
<noscript>
<a href="https://www.livechatinc.com/chat-with/11412593/" rel="nofollow">Chat with us</a>,
powered by <a href="https://www.livechatinc.com/?welcome" rel="noopener nofollow" target="_blank">LiveChat</a>
</noscript>
<!-- End of LiveChat code -->

	
</body>


</html>