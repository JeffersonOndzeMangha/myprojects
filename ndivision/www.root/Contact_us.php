<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>

<!--stylesheet links-->
	<link href="styles.css/style.css" rel="stylesheet" type="text/css"/>
<!--end of link lists-->
</head>

<body>
	<!--background video-->
    <div id="background">
        <video class="live-background" preload="auto" autoplay loop muted poster="mediafiles/icons/social-facebook-box-white-icon72px.png">
              <source src="http://techslides.com/demos/sample-videos/small.webm" type="video/webm"/>
              <source src="http://techslides.com/demos/sample-videos/small.ogv" type="video/ogg"/> 
              <source src="http://techslides.com/demos/sample-videos/small.mp4" type="video/mp4"/>
              <source src="http://techslides.com/demos/sample-videos/small.3gp" type="video/3gp"/>
        </video><!--end video tag-->
    </div><!--end background dic-->
    
    <!--container(containes all content includeing sections, basicaly wraps the entire page)-->
    
    <div class="container">
    
    <!--sections: different sections on the page-->
    
    	<?php Include("/includefiles/header.inc.php"); ?>
        
        <section class="section" id="section1">
        	
        	<div class="section-content-wrapper">
        		<h1> Designing the most amazing websites since 2016 </h1>
                <p> We take pride in designing the most amazing websites for our clients. Not only do our clients get the website of their dreams
                they get it on their budget. We take our job very seriously, and take pride in making sure we publish the most amazing of our
                products. </p>
                <a href=""><h3>LEARN MORE</h3></a>
        	</div><!--end section content wrapper div-->
            
        </section><!--end section1-->
        
        <section id="section2"> 
                <video id="gif1" autoplay loop muted>
                <source src="https://giphy.com/gifs/l2QZU8aMKDjntpUbe/html5" type="video/mp4"/></video>
            <div class="section-content-wrapper">

				<h1>Our Services</h1>
                <p> We have multiple services to provide our clients, from website developement, to covering social media aspects of the client's
                business. We also maintaine websites for our clients for a monthly fee. We can also provide hostinf for client websites. There 
                are many more services, and if we don't offer it, you can always request custom services, we can then inform you if we are 
                capable to fulfill your needs.</p>
                <a href=""><h3>SEE ALL</h3></a>
        	</div><!--end section content wrapper div-->
            
        </section><!--end section2-->
        
        <section class="section" id="section3">
        	<div class="section-content-wrapper">
        		<h1>Our Approach</h1>
                <p>Similar to terms such as "big data" and "the cloud", the definition of "digital" is frequently misunderstood and seemingly
                 boundless.</p>
                <p>We've demystified the complex universe of "digital" with our proprietary frameworks throughout our solutions and services,
                 to help our clients understand and execute true digital transformation.</p>
                 <a href=""><h3>THE PROCESS</h3></a>
        	</div><!--end section content wrapper div-->
            
        </section><!--end section3-->
        
        <section class="section" id="section4">
        	<div class="section-content-wrapper">
        		<h1>None Sense for Space Holding</h1>
                <p>Increase Your Digital IQ! Get Digital Trends Free Increase your digital intelligence, get the info soundbites you need to
                 rock the board meeting, impress your boss and keep up with your team.Get the day's key stat and soundbite commentary about the
                 latest digital trends, delivered directly to your inbox.</p>
                <p>Like many large traditional corporations, our digital presence grew into many separate websites, mobile apps and social media
                 profiles. Consumer care about one brand, not a company’s structure, so our digital experience should adapt to that expectation.
                 We engaged Centric Digital to define an enterprise-wide digital strategy & multi-year roadmap to integrate our digital customer
                 experience. With Centric Digital’s help we’ve achieved our goals and been rated #1 in customer experience in our industry.
                 Digital Marketing Director, Fortune 500 Corporation.</p>
                <p>Game changing ideas typically come from external perspective. We've delivered digital transformation for premier domestic,
                 global and emerging brands across multiple industries.
                </p>
        	</div><!--end section content wrapper div-->
            
        </section><!--end section4-->
        
        <?php include("/includefiles/footer.inc.php"); ?>
    
    </div> <!--end container div-->

<script src="styles.js/style.js" rel="stylesheet" type="text/javascript"></script>
</body>

</html>
