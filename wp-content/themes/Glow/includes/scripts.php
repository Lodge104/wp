<?php global $shortname; ?>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.1/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/jquery.easing.1.3.js"></script>	
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/superfish.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/init.js"></script>
<?php if ((is_home()) && (get_option('glow_featured') == 'on') ) { //scripts for featured area are loaded only on homepage / when Display Featured Articles is set to Display ?>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/jquery.cycle.all.min.js"></script>
<script type="text/javascript">
jQuery(window).load(function(){
	var $featured_item = jQuery('#featured-area div#slider-control div.featitem'),
		$slider_control = jQuery('#featured-area div#slider-control'),
		$image_container = jQuery('div#s1 > div');

	$featured_item.find('img').fadeTo("fast", 0.7);
	$slider_control.find("div.featitem.active img").fadeTo("fast", 1);
	$image_container.css("background-color","#000000");
		
	et_featured_options = {
		timeout: 0,
		speed: 300,
		cleartypeNoBg: true,
		before: function (currSlideElement, nextSlideElement, options, forwardFlag) { 
			var $et_active_slide = jQuery(nextSlideElement),
				et_active_order = $et_active_slide.prevAll().length,
				$this_element = $slider_control.find('div.featitem').eq(et_active_order);

			$slider_control.find("div.featitem.active img").fadeTo("fast", 0.7);
			$slider_control.find("div.featitem.active").removeClass('active');
			$this_element.addClass('active');
			$slider_control.find("div.featitem.active img").fadeTo("fast", 1);
		},
		fx: '<?php echo esc_js(get_option($shortname.'_slider_effect')); ?>'
	}
	
	<?php if (get_option($shortname.'_pause_hover') == 'on') { ?>
		et_featured_options.pause = 1;
	<?php } ?>
	
	<?php if (get_option($shortname.'_slider_auto') == 'on') { ?>
		et_featured_options.timeout = <?php echo esc_js(get_option($shortname.'_slider_autospeed')); ?>;
	<?php } ?>
	
	jQuery('#s1, #slider-control').show();
	jQuery('#featured-area').css( 'backgroundImage', 'none' );
	jQuery('#s1').cycle(et_featured_options);
	
	$image_container.hover(
		function () {
			jQuery(this).find("img").fadeTo("fast", 0.7);
		}, 
		function () {
			jQuery(this).find("img").fadeTo("fast", 1);
		}
	);
	
	$featured_item.click(function() {
		var et_ordernumber = jQuery(this).prevAll().length;
		jQuery('#s1').cycle(et_ordernumber);
		return false;
	});
<?php if (get_option('glow_disable_toptier') == 'on') echo('jQuery("ul.nav > li > a > span.sf-sub-indicator").parent().attr("href","#")'); ?>
});
</script>

<?php }; ?>

<?php if (get_option('glow_disable_toptier') == 'on') { ?>
   <script type="text/javascript">
      <?php echo('jQuery("ul.nav > li > ul").prev("a").attr("href","#");'); ?>
   </script>
<?php }; ?>