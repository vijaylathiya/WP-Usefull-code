<?php
// get the current taxonomy term
$term = get_queried_object();


// vars
$title = get_field('title', $term);
$catslug = get_field('post_category_slug', $term);
$housesizecontent = get_field('house_size_content', $term);


$phone_number = get_field('phone_number');
$email_address = get_field('email_address');


 get_header(); ?>

<div class="galleryCont">
   <div class="accordion" id="accordionFolium">
  <div class="card z-depth-0 bordered one">
    <div class="card-header" id="headingOne">

     
    <!-- <h3>The Folium Five <sup>TM</sup></h3>-->
           <h1><?php single_term_title(); echo " Gutter Cleaners ";
           //the_field('folium_accordion_heading');?></h1> 
     

    </div>
    <div  aria-labelledby="headingOne" data-parent="#accordionFolium">
      <div class="card-body">
        <div class="msa-specific smaller">
  <div class="container">

     <div class="sec-title"><?php echo $title; ?></div>
    <div class="table-responsive">
<table class="table">
<thead class="heading item">
  <tr class="post-inner">
    <th class="head"><div><h6>Company</h6></div></th>
    <th class="firstth"><div class="price">
<h6>Price</h6>
<?php /*?><div class="switch-btn">
<small class="top">click to</small>
<label class="switch">
  <input type="checkbox">
  <span class="round-btn"></span>
</label>
<small class="bottom">switch</small>

</div>
<div class="house-heading smaller-house">
<span>Average House:</span>
<?php the_field('smaller_house_content');?>
</div>

<div class="house-heading larger-house">
<span>Larger House:</span>
<?php the_field('larger_house_content');?>
</div><?php */?>
   </div></th>

    <th class="head"><div class="contact">
          <h6>contact</h6>
          </div></th>

    <th class="head"><div class="rating">
    <h6>Google Rating</h6>
    </div></th>
  </tr>
 </thead>
 <tbody id="innerdivresponce" class="item">
<?php
$term_id = get_queried_object()->term_id;
  $args = array(
      'post_type' => 'msa_specific',
      'posts_per_page' => -1,
    //'orderby'   => 'meta_value',
      'orderby'   => 'meta_value_num',
 'meta_key'  => 'smaller_house_amount',
	//'meta_key'  => 'larger_house_amount',
	'meta_type' => 'Binary',
       'order' => 'ASC',
      'tax_query' => array(
        array(
          'taxonomy' => 'msa_specificm',
          'field' => 'id',
          'terms' => get_queried_object()->term_id

        )
      )
    );
$testimoniallist = new WP_Query($args);
if($testimoniallist->have_posts()) : while($testimoniallist->have_posts()): $testimoniallist->the_post();

?>


  <tr  class="post-inner">
    <td data-title="Company"><a href="<?php the_field('website') ?>" target="_blank"><?php the_title();?></a></td>
    <td data-title="price"><div class="price larger-house">
  <div class="progress-bar" role="progressbar" style="width:<?php the_field('larger_house_amount_bar');?>%" aria-valuenow="<?php the_field('larger_house_amount_bar');?>" aria-valuemin="0" aria-valuemax="100"></div>
<div class="amount">$ <?php the_field('larger_house_amount');?></div>
          </div><div class="price smaller-house">
  <div class="progress-bar" role="progressbar" style="width:<?php the_field('smaller_amount_bar');?>%" aria-valuenow="<?php the_field('smaller_amount_bar');?>" aria-valuemin="0" aria-valuemax="100"></div>
<div class="amount">$ <?php the_field('smaller_house_amount');?></div>
          </div></td>

          <td data-title="Contact"><div class="contact">
          <a href="tel:<?php the_field('phone_number');?>">
            <img src="<?php bloginfo('template_url');?>/assets/images/phone-icon.png" alt="phone_icon"></a>
<?php
$prop_det_url = get_field('email_address');
if($prop_det_url!=''){ ?>

<a href="mailto:<?php the_field('email_address');?>?subject=Gutter Cleaning Quote Request"><img alt="email_icon" src="<?php bloginfo('template_url');?>/assets/images/email-icon.png"></a>

<?php } else { ?>
<a href="<?php the_field('website') ?>" target="_blank"><img alt="" src="<?php bloginfo('template_url');?>/assets/images/email-icon.png"></a>



<?php } ?>

      



          </div></td>

      <td data-title="Google Rating">
        <div>
        <script src="https://apps.elfsight.com/p/platform.js" defer></script>
        <div class="<?php the_field('google_place');?>"></div>
        <?php /*?>  <a href="<?php the_field('google_place_link');?>">
            <?php echo do_shortcode(get_field('google_place')); ?>
          </a><?php */?>
        </div>
	</td>
  </tr>
  <?php endwhile; endif; wp_reset_query(); ?>
  </tbody>
</table>
</div>
     <div class="folium-content">
     <?php echo $housesizecontent; ?>
 </div>
<?php
the_archive_description( '<div class="taxonomy-description">', '</div>' );
//the_field('folium_content');

$emailsec = $howtodecide = $helpfulbs ='';
$lscat_metas_ser = get_term_meta($term_id, 'lscat_metas', true);
echo "<pre>";
print_r($lscat_metas_ser);
if(isset($lscat_metas_ser) && $lscat_metas_ser!='')
{
	$lscat_metas = unserialize($lscat_metas_ser);
	$emailsec = $lscat_metas['lwcc_emailsec'];
	$howtodecide = $lscat_metas['lwcc_howtodecide'];
	$helpfulbs = $lscat_metas['lwcc_helpfulbs'];
}

?>
</div>
</div>
      </div>
      <?php if(isset($emailsec) && $emailsec!='yes') { ?>
      <!-- Email signup Start -->
      <div class="join-our-list" style="background-image: url(<?php if(get_field('recent_post_background')) { the_field('recent_post_background'); } else { bloginfo('template_url');?>/assets/images/leef-bg2.jpg<?php } ?>)">

        <div class="container">
          <?php the_field('join_our_content');?>
<div class="input-box">
 <?php //the_field('join_our_form');
echo do_shortcode('[contact-form-7 id="252" title="enter your email"]');

 ?>

</div></div>
</div>
	<!-- Email signup Over  -->
    <?php } ?>
    </div>
  </div>
  <!--<div class="card z-depth-0 bordered two">
    <div class="card-header" id="headingTwo">

        <button>
          <h3>Map<?php //the_field('map_accordion_heading');?></h3>
        </button>

    </div>
    <div id="" class="" aria-labelledby="headingTwo" data-parent="#accordionFolium">
      <div class="card-body">
       <div class="container">
      <div class="page-heading">
      <h2 class="page-title"><?php /*the_field('map_title');*/?></h2>
      <h4><?php /*the_field('sub_headline');*/?></h4>
    </div>
   <?php /*?> <?php echo do_shortcode('[ASL_STORELOCATOR template="6"]');?><?php */?>

	<?php/*
	$category = get_queried_object();
	$term_vals = get_term_meta($category->term_id);
	$mapid = $term_vals['map'][0];*/
?>

    <?php /*echo do_shortcode($mapid);*/ ?>

          </div>
      </div>
    </div>
  </div>-->
  
<!-- How to Decide Start -->
  <div class="card z-depth-0 bordered three">
    <div class="card-header" id="headingThree">

        <button >
           <h3>How to Decide<?php //the_field('evaluation_accordion_heading');?></h3>
        </button>

    </div>
    <div aria-labelledby="headingThree" data-parent="#accordionFolium">
      <div class="card-body">
      <div class="container">
      <div class="page-heading">
      <h2 class="page-title">Choosing Your Provider</h2>
    </div>
    <div class="text-center">
       <p>The information we collected above should make it easier to choose a gutter cleaner.<br>
As you make your decision, though, there are several factors we recommend you consider</p>
</div>       <div class="evaluation-box">

            <div class="row">
        <div class="col-md-4 image delay1 wow fadeInLeft duration1 animated animated" style="visibility: visible;">
          <div class="circle">
            <div class="thumb"> <img src="/wp-content/uploads/2021/04/quality.jpg" alt="Quality"> </div>
          </div>
        </div>
        <div class="col-md-8 item delay1 wow fadeInRight duration1 animated animated" style="visibility: visible;">
          <div class="detail">
            <h3>Quality</h3>
<p>Does the vendor<br>
Offer a service guarantee (e.g., 30-day no-clog guarantee)?<br>
Flush gutters with water before finishing the job?<br>
Bag debris, rather than blowing it into your landscaping?<br>
Have a valid insurance policy that protects its employees and you?</p>
          </div>
        </div>
      </div>
            <div class="row">
        <div class="col-md-4 image delay1 wow fadeInLeft duration1 animated animated" style="visibility: visible;">
          <div class="circle">
            <div class="thumb"> <img src="/wp-content/uploads/2021/04/reliability-1.jpg" alt="Reliability"> </div>
          </div>
        </div>
        <div class="col-md-8 item delay1 wow fadeInRight duration1 animated animated" style="visibility: visible;">
          <div class="detail">
            <h3>Reliability</h3>
<p>Does the vendor<br>
Answer the phone when you call?<br>
Notify you via call, text or email, before they show up?</p>
          </div>
        </div>
      </div>
            <div class="row">
        <div class="col-md-4 image delay1 wow fadeInLeft duration1 animated animated" style="visibility: visible;">
          <div class="circle">
            <div class="thumb"> <img src="/wp-content/uploads/2021/04/price-1.jpg" alt="Price"> </div>
          </div>
        </div>
        <div class="col-md-8 item delay1 wow fadeInRight duration1 animated animated" style="visibility: visible;">
          <div class="detail">
            <h3>Price</h3>
<p>Price is top of mind when hiring a gutter cleaner and should be balanced with a vendor's quality and reliability </p>
          </div>
        </div>
      </div>
          </div></div>

      </div>
    </div>
  </div>
<!-- How to Decide Over  -->

<!-- Helpful Blog posts Start  -->
  <div class="card z-depth-0 bordered four">
    <div class="card-header" id="headingFour">

<button >
          <h3>Helpful Blog Posts</h3>
        </button>

    </div>
    <div aria-labelledby="headingFour" data-parent="#accordionFolium">
      <div class="card-body">
      <div class="container">
<?php /*?>      <div class="page-heading"><h2 class="page-title"><?php the_field('helpful_accordion_heading');?></h2></div>
		  <div class="text-center helpful-headline">
			  <?php the_field('helpful_sub_headline');?>
		  </div><?php */?>
 	<div class="row blog-page">

 <?php
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$the_query = new WP_Query( array(
'post_status' => 'publish',
'posts_per_page' =>4,
'paged'=>$paged,
'meta_query' => array(
array(
'compare' => '=')))) ?>

<?php  if ( $the_query->have_posts() ) : while ( $the_query->have_posts() ) : $the_query->the_post(); ?>

<div class="col-lg-6 col-md-6 post-item wow fadeInUp duration1 delay<?php echo $i;?>">

<div class="post-inner row">
<div class="col-lg-4 col-md-12">
<div class="post-thumbnail">
<div class="thumb">
<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
<?php if(has_post_thumbnail()) { the_post_thumbnail('blog-thumb'); } else { ?>
<img alt="" src="http://folium.dev-first-cut.com/wp-content/uploads/2021/09/Gutter-Cleaning.jpg" class="" alt="blog-thumb">
<?php } ?></a>
</div></div></div>
<div class="col-lg-8 col-md-12">
<div class="blog-item-body">
<h3 class="blog-item-title"><a href="<?php the_permalink();?>"><?php the_title();?></a></h3>
<p><?php echo wp_trim_words( get_the_content(), 9) ?></p>
<div class="blog-item-foot"> <a href="<?php the_permalink();?>">Read Article</a> </div>
</div>
</div>
</div>
</div>
  <?php endwhile; endif; ?>
       <!--     <?php //wp_reset_postdata(); ?>

        <?php //else : ?>
            <p><?php// __('No News'); ?></p>
        <?php //endif; ?> -->

</div>
</div>
      </div>
  </div>
  </div>
<!-- Helpful Blog posts Over  -->  
  
</div>


<div class="folium-content py-5">
      <div class="container">
       <?php
while ( have_posts() ) : the_post();
the_content();
endwhile;
?>
     </div></div>
 </div>

<script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
<script>
  jQuery(document).ready(function(){



	  var vars = '<?php echo $_REQUEST['searchzip'] ?>';
    //getajaxvalues(vars);
    //return vars;


jQuery('.findplace').on('click' ,  function(){

      var getzipcode = jQuery(this).prev('.getzipvalue').val();

      getajaxvalues(getzipcode);



  })


function getajaxvalues(getzipcode){

     jQuery.ajax({
            type: "POST",
            //async: false,
            //dataType: 'JSON',
            beforeSend: function() {
                      jQuery('body').addClass("loading");
                      jQuery('body').append('<div class="modalgif"></div>');
                    },
              complete: function() {
                jQuery('body').removeClass("loading");
                jQuery('.modalgif').remove();
              },
            url: '/wp-admin/admin-ajax.php',
            data: {'action':'getlocationdata' , 'zipcode' : getzipcode},
            success: function(data){
              //alert(data);
              jQuery('#innerdivresponce').html(data);
            }
        });

}

})

</script>
<?php get_footer(); ?>