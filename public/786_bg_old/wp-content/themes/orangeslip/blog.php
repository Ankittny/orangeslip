<?php
/*

Template Name:  Blog Page


*/

 
get_header(); ?>


<div class="container">

<ul id="breadcrumb">
  <li><a href="<?php echo home_url(); ?>"><span class="icon icon-home"><img src="<?php echo get_template_directory_uri() . '/assets/img/home-new.png'; ?>" /></a<</span></a></li>
  <li><a><span class="icon icon-beaker"> </span> Blogs</a></li>


</ul>
<div class="inner-header">Recent Blogs</div>
<?php if ( function_exists( 'wpsp_display' ) ) wpsp_display( 6 ); ?>
</div>
 
<?php get_footer(); ?>