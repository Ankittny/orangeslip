<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */

get_header(); ?>

<section id="home">
        <div class="section-1-img middle_text">
            <div class="section-1-bg middle_text">
                <div class="container">
                    <div class="banner-text">
                        <div class="row">
                            <div class="col-sm-12 col-md-6 col-lg-6">
                                <h5 class="heading-section-2">Download the Cricdope app NOW to get a precise match prediction</h5>
                                <p>Cricdope is the best cricket prediction app, where you'll find in-depth analyses of upcoming cricket matches from our experts. We will provide you with well-researched match analyses and predictions following the pitch
                                    reports, weather reports, previous match reports, etc. With our analysis, you can rest assured that you will win more money by PLAYING the game wisely. DOWNLOAD the app right away.
                                </p>

                                <div class="row">



                                    <div class="col-sm-12 col-md-12">
                                        <div class="get-app-button-link">

                                            <a class="btn btn-google" href="https://play.google.com/store/apps/details?id=com.cricdope" title="Google Play">Play Store</a>
                                            <a class="btn btn-apple" href="#" title="Google Play">App Store</a>
                                        </div>
                                    </div>

                                </div>

                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-6">
                           
                                <img  class="hand-mocup-img"  src="<?php echo get_template_directory_uri() . '/assets/img/hand-mocup.png'; ?>" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section class="fantasy_topic pt-5 pb-5" id="About">
        <div class="container">
            <div class="fantasy_topic_img">
                <!-- <img src="{{asset('frontend/img/logo.png')}}" alt="image"> -->
                <h4>ABOUT US</h4>
            </div>
            <div class="fantasy_topic_paragraph">
                <p>Fantasy cricket games are booming with the passing of time. The main objective in fantasy cricket is to score as many fantasy points as possible and rank high on the leaderboard to earn more. Fantasy sports is a game of skill that requires
                    players to utilize their sports knowledge to play well and win. Here comes the Cricdope, at your service to give you precise analysis and match predictions.</p>

                <p>This platform offers the best single and accumulator predictions for the sport. The app gives predictions by conducting a thorough analysis, with a high success rate since 2010. We have experts who have a good understanding of the game
                    and analyze it by tracking several factors, such as recent five matches, head-to-head matches, pitch and weather reports, etc.</p>

                <p>We are trusted by over 10,000+ users, and you can count on us too. With the precise match analysis and predictions of Cricdope, you can create fantasy teams and win every day.</p>

            </div>
        </div>
    </section>


    <section>
        <div class="container" style="padding-bottom: 35px;">
            <div class="row justify-content-evenly">
                <h2 class="heading-1">Get our expert panel's match prediction to play secure and earn more</h2>

                <p class="paragraph-1">Since 2010, we have established a solid reputation for providing accurate cricket match predictions. Our drive to complete a thorough analysis of upcoming matches and pay close attention to every significant element makes us trustworthy.
                    Before presenting match predictions, our expertise looks into all relevant factors, such as pitch and weather reports, head-to-head match details, etc.</p>

                <div class="col-sm-12 col-md-4 col-lg-4 fallow-step">
                    <div class="card">

                    <img  src="<?php echo get_template_directory_uri() . '/assets/img/img1.png'; ?>" class="card-img-top img-card-1" alt="">
                    
                        <div class="card-body">
                            <h5 class="card-title">10,000+ <span style="color: #eb4a4b;">Users </span> </h5>
                            <p class="card-text">We have successfully crossed 10,000+ users by giving them proper match analysis and leading them to earn more money by playing.</p>
                            <!-- <img class="select_match_img" src="{{asset('frontend/img/selectamatch.png')}}" alt=""> -->
                        </div>
                    </div>
                </div>

                <div class="col-sm-12 col-md-4 col-lg-4 fallow-step">
                    <div class="card">
                    <img  src="<?php echo get_template_directory_uri() . '/assets/img/img2.png'; ?>" class="card-img-top img-card-1" alt="">
                        
                        <div class="card-body">
                            <h5 class="card-title">Reliable <span style="color: #eb4a4b;"> Match Analyzer</span> </h5>
                            <p class="card-text">Crickdope has been providing detailed match analysis and predictions since 2010. Our expert panel conducts thorough research before giving match predictions.</p>
                            <!-- <img class="creat_team_img" src="{{asset('frontend/img/createteam.jpg')}}" alt=""> -->
                        </div>
                    </div>
                </div>

                <div class="col-sm-12 col-md-4 col-lg-4 fallow-step">
                    <div class="card">
                    <img  src="<?php echo get_template_directory_uri() . '/assets/img/img3.png'; ?>" class="card-img-top img-card-1" alt="">
                      
                        <div class="card-body">
                            <h5 class="card-title">Trustworthy</h5>
                            <p class="card-text">Our expert cricket match analysis and predictions are precise enough to make us the most trustworthy and secure match predictions.</p>
                            <!-- <img class="win_big_img" src="{{asset('frontend/img/cash-withdrawal.jpg')}}" alt=""> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>







    <section class="Performance pt-5 pb-5">
        <div class="container">
            <div class="row">
                <h2 class="service1">Ways we analyze and predict the match</h2>
                <p class="product_paragraph">Cricdope provides a detailed analysis of every upcoming match. You’ll gain exclusive insight into our research and analysis processes, which ultimately help us determine the most probable outcome. Here's how our match predictions work:</p>
                <div class="col-sm-12 col-md-6 col-lg-6">
                    <div class="service">

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <div class="card example-1 scrollbar-ripe-malinka">
                                    <div class="card-body">
                                        <h4 id="section1"><strong>Recent Performances:</strong></h4>
                                        <p>We analyze the recent performances of both teams. Here, we show the results of the wins or losses of the teams in the last five matches.</p>

                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-4">
                                <div class="card example-1 scrollbar-ripe-malinka">
                                    <div class="card-body">
                                        <h4 id="section1"><strong>Head-to-Head Matches:</strong></h4>
                                        <p>Our analyzers take a look at the last five head-to-head matches. It helps the analyzer research the performance of the individual teams against each other.</p>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <div class="card example-1 scrollbar-ripe-malinka">
                                    <div class="card-body">
                                        <h4 id="section1"><strong>Teams match at stated venues and results:</strong></h4>
                                        <p>We also analyze the previous head-to-head match between both teams at the stated venue. The results help us analyze the team's performance in the upcoming match.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-4">
                                <div class="card example-1 scrollbar-ripe-malinka">
                                    <div class="card-body">
                                        <h4 id="section1"><strong>Pitch and Weather:</strong></h4>
                                        <p>We are aware that pitch and weather play a crucial role in any team's performance. So, we conduct a thorough analysis of the pitch and weather to let the user know the chances of good batting/fielding/bowling conditions.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="col-sm-12 col-md-6 col-lg-6">
                <img  src="<?php echo get_template_directory_uri() . '/assets/img//perform.png'; ?>" class="" alt="">
                 
                </div>
            </div>
        </div>
    </section>

    <section class="pt-5 pb-5" id="plans">
        <div class="container">
            <div>
                <h2 class="product">Membership Plans</h2>
                <p class="product_paragraph">Cricdope covers all kinds of cricket matches. Thus, you will be sure to get the analysis and predictions of all cricket matches. Some of the match analysis is free, whereas others are covered under the premium plans. Check out our minimal-cost
                    membership plans to get unlimited access to the match analysis. </p>
            </div>
            <div class="owl-carousel owl-theme">
                <div class="item">
                    <div class="card">

                        <h5 class="card-header">Weekly Plan</h5>
                        <div class="card-body">
                            <!-- <img class="product-img" src="img/bad-visualization.png" alt=""> -->
                            <h3>₹ 49/Week</h3>
                            <p class="card-text">Users will get full access to unlimited match analysis and predictions for 7 days.</p>
                        </div>
                    </div>
                </div>
                <div class="item">
                    <div class="card">
                        <h5 class="card-header">Monthly Plan</h5>
                        <div class="card-body">
                            <!-- <img class="product-img" src="img/players-profile.png" alt=""> -->
                            <h3>₹ 149/Month</h3>
                            <p class="card-text">Get 28 days of unlimited access to all kinds of cricket analysis and predictions.</p>
                        </div>
                    </div>
                </div>
                <div class="item">
                    <div class="card">
                        <h5 class="card-header">Yearly Plan </h5>
                        <div class="card-body">
                            <!-- <img class="product-img" src="img/player-data.png" alt=""> -->
                            <h3>₹ 999/Year</h3>
                            <p class="card-text">Access the app for 365 days and get precise match analysis and predictions.</p>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </section>











<div class="new-section">
  <div class="container">
<div class="recent heading_text"> <h2>Recent Post </h2></div>
<?php if ( function_exists( 'wpsp_display' ) ) wpsp_display( 43 ); ?>
<div class="text-center mb-5">
  <a href="blog" class="view-more">view more</a>
</div>
</div>

</div>


	<div id="primary" class="content-area episode-new">
  


<?php //get_sidebar(); ?>
<?php get_footer(); ?>
