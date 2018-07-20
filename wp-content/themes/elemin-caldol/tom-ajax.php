<?php
/*
Template Name: tom-ajax
*/
?>

<?php get_header(); ?>

<?php
/** Themify Default Variables
 *  @var object */
global $themify; ?>

<!-- layout-container -- page.php -->
<div id="layout" class="clearfix">	

<
	<?php themify_content_before(); //hook ?>
	<!-- content -->
	<div id="content" class="clearfix">
<form action="<?php echo site_url() ?>/wp-admin/admin-ajax.php" method="POST" id="filter">
	<?php
		if( $terms = get_terms( 'topic-tag', 'orderby=name' ) ) : // to make it simple I use default categories
			echo '<select name="categoryfilter"><option>Select category...</option>';
			foreach ( $terms as $term ) :
				echo '<option value="' . $term->term_id . '">' . $term->name . '</option>'; // ID of the category as the value of an option
			endforeach;
			echo '</select>';
		endif;
	?>
	<input type="text" name="price_min" placeholder="Min price" />
	<input type="text" name="price_max" placeholder="Max price" />
	<label>
		<input type="radio" name="date" value="ASC" /> Date: Ascending
	</label>
	<label>
		<input type="radio" name="date" value="DESC" selected="selected" /> Date: Descending
	</label>
	<label>
		<input type="checkbox" name="featured_image" /> Only posts with featured image
	</label>
	<button>Apply filter</button>
	<input type="hidden" name="action" value="myfilter">
</form>
<div id="response"></div>	
</div>
</div>
<!-- /layout-container -->
	
<?php get_footer(); ?>
