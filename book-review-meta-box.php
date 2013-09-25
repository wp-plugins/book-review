<?php
/**
 * Calls the class on the post edit screen.
 */
function call_BookReviewAdmin() 
{
    return new BookReviewAdmin();
}

if (is_admin()) {
    add_action('load-post.php', 'call_BookReviewAdmin');
    add_action('load-post-new.php', 'call_BookReviewAdmin');
}

class BookReviewAdmin
{
    public function __construct()
    {
	add_action('save_post', array(&$this, 'book_review_save_meta_box'));
        add_action('add_meta_boxes', array(&$this, 'book_review_add_meta_box'));
    }

    /**
     * Adds the meta box container
     */
    public function book_review_add_meta_box()
    {
        add_meta_box( 
            'book-review-meta-box',
            'Book Info',
            array(&$this, 'book_review_render_meta_box'),
            'post',
            'normal',
            'high'
        );
    }

    /**
     * Callback function to show fields in meta box.
     */
    public function book_review_render_meta_box($post) 
    {	
	$values = get_post_custom($post->ID);
	$links = get_option('book_review_links');
	$ratings = get_option('book_review_ratings');
	$num_links = $links['book_review_num_links'];
	$link_urls = array();
	
	for ($i = 1; $i <= 5; $i++) {
	    $link_urls[$i] = isset($values['book_review_link' . $i]) ? esc_url($values['book_review_link' . $i][0]) : '';
	}
	
	$title = isset($values['book_review_title']) ? $values['book_review_title'][0] : '';
	$series = isset($values['book_review_series']) ? $values['book_review_series'][0] : '';
	$author = isset($values['book_review_author']) ? $values['book_review_author'][0] : '';
	$genre = isset($values['book_review_genre']) ? $values['book_review_genre'][0] : '';
	$publisher = isset($values['book_review_publisher']) ? $values['book_review_publisher'][0] : '';
	$format = isset($values['book_review_format']) ? $values['book_review_format'][0] : '';
	$pages = isset($values['book_review_pages']) ? $values['book_review_pages'][0] : '';
	$source = isset($values['book_review_source']) ? $values['book_review_source'][0] : '';
	$release_date = isset($values['book_review_release_date']) ? $values['book_review_release_date'][0] : '';
	$cover_url = isset($values['book_review_cover_url']) ? esc_url($values['book_review_cover_url'][0]) : '';	
	$summary = isset($values['book_review_summary']) ? $values['book_review_summary'][0] : '';
	$rating = isset($values['book_review_rating']) ? $values['book_review_rating'][0] : '';
	$args = array(
	    "textarea_rows" => 15,
	    "media_buttons" => false
	);
	
	//We'll use this nonce field later on when saving.  
	wp_nonce_field('save_meta_box_nonce', 'book-review-meta-box-nonce');
	
	?>
	<label for="book_review_title">Title<span class="required">*</span>:</label>
	<input type="text" id="book_review_title" name="book_review_title" value="<?php echo $title; ?>" />
	<br />
	<label for="book_review_series">Series:</label>
	<input type="text" id="book_review_series" name="book_review_series" value="<?php echo $series; ?>" />
	<br />
	<label for="book_review_author">Author:</label>
	<input type="text" id="book_review_author" name="book_review_author" value="<?php echo $author; ?>" />
	<br />
	<label for="book_review_genre">Genre:</label>
	<input type="text" id="book_review_genre" name="book_review_genre" value="<?php echo $genre; ?>" />
	<br />
	<label for="book_review_publisher">Publisher:</label>
	<input type="text" id="book_review_publisher" name="book_review_publisher" value="<?php echo $publisher; ?>" />
	<br />
	<label for="book_review_release_date">Release Date:</label>
	<input type="text" id="book_review_release_date" name="book_review_release_date" value="<?php echo $release_date; ?>" />
	<br />
	<label for="book_review_format">Format:</label>
	<input type="text" id="book_review_format" name="book_review_format" value="<?php echo $format; ?>" />
	<br />
	<label for="book_review_pages">Pages:</label>
	<input type="text" id="book_review_pages" name="book_review_pages" value="<?php echo $pages; ?>" />
	<br />
	<label for="book_review_source">Source:</label>
	<input type="text" id="book_review_source" name="book_review_source" value="<?php echo $source; ?>" />
	<br />
	
	<?php
	    //Render HTML elements outside of PHP code, otherwise they will be slightly misaligned.
	    for ($i = 1; $i <= 5; $i++) {
		if ($links['book_review_link_text' . $i] && ($num_links >= $i)) { ?>
		    <label for="<?php echo 'book_review_link' . $i; ?>">
			<?php echo $links["book_review_link_text" . $i]; ?> URL:
		    </label>
		    <input type="text" id="<?php echo 'book_review_link' . $i; ?>" name="<?php echo 'book_review_link' . $i; ?>" value="<?php echo $link_urls[$i]; ?>" />
		    <br />
		<?php } 
	    }
	?>
	
	<label for="book_review_cover_url">Cover URL:</label>
	<input type="text" id="book_review_cover_url" name="book_review_cover_url" value="<?php echo $cover_url; ?>" />
	<a href="#" class="button button-small upload-image-button">Upload Cover</a>
	<br />
	
	<?php
	    //Show the image preview.
	    if ($cover_url == "") {
		$style = 'display: none;';
	    }
	    else {
		$style = 'display: block;';
	    }	    
	?>
	
	<img id="book_review_cover_image" src="<?php echo $cover_url; ?>" style="<?php echo $style; ?>" />
	<br />
	<label for="book_review_summary" class="summary">Synopsis:</label>
	<?php wp_editor($summary, "book_review_summary", $args); ?>
	<br />
	<label for="book_review_rating">Rating:</label>	
	<select id="book_review_rating" name="book_review_rating">
	    <?php
		$items = array("-1" => "Select...", "1" => "1", "2" => "2", "3" => "3", "4" => "4", "5" => "5");
		
		foreach($items as $type => $item) {
		    $selected = ($rating == $type) ? 'selected="selected"' : '';
		    echo "<option value='" . $type . "' " . $selected . ">" . $item . "</option>";
		}
	    ?>
	</select>
	<br />
	
	<?php
	    if ($ratings['book_review_rating_default'] == "1") {
		if ($rating == "1") {
		    $src = plugins_url('images/one-star.png', __FILE__ );
		}
		else if ($rating == "2") {
		    $src = plugins_url('images/two-star.png', __FILE__ );
		}
		else if ($rating == "3") {
		    $src = plugins_url('images/three-star.png', __FILE__ );
		}
		else if ($rating == "4") {
		    $src = plugins_url('images/four-star.png', __FILE__ );
		}
		else if ($rating == "5") {
		    $src = plugins_url('images/five-star.png', __FILE__ );
		}			
	    }
	    else {
		if ($rating == "1") {
		    $src = $ratings['book_review_rating_image1'];
		}
		else if ($rating == "2") {
		    $src = $ratings['book_review_rating_image2'];
		}
		else if ($rating == "3") {
		    $src = $ratings['book_review_rating_image2'];
		}
		else if ($rating == "4") {
		    $src = $ratings['book_review_rating_image2'];
		}
		else if ($rating == "5") {
		    $src = $ratings['book_review_rating_image2'];
		}
	    }
	    
	    //Show the rating image.
	    if (isset($src)) {
		$style = 'display: block;';
	    }
	    else {
		$style = 'display: none;';
	    }	    
	?>
	
	<img id="book_review_rating_image" src="<?php echo $src; ?>" style="<?php echo $style; ?>" />
	<br />		
	<?php
    }
    
    function book_review_save_meta_box($post_id)
    {
	//Bail if we're doing an auto save.
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
	
	//If our nonce isn't there, or we can't verify it, bail.
	if (!isset($_POST['book-review-meta-box-nonce']) || !wp_verify_nonce($_POST['book-review-meta-box-nonce'], 'save_meta_box_nonce')) return;
	
	//If our current user can't edit this post, bail.
	if (!current_user_can('edit_post')) return;
	
	// Make sure your data is set before trying to save it.
	if (isset($_POST['book_review_title']))
	    update_post_meta($post_id, 'book_review_title', sanitize_text_field($_POST['book_review_title'])); 
	
	if (isset($_POST['book_review_series']))
	    update_post_meta($post_id, 'book_review_series', sanitize_text_field($_POST['book_review_series'])); 
	    
	if (isset($_POST['book_review_author']))
	    update_post_meta($post_id, 'book_review_author', sanitize_text_field($_POST['book_review_author']));
	    
	if (isset($_POST['book_review_genre']))
	    update_post_meta($post_id, 'book_review_genre', sanitize_text_field($_POST['book_review_genre'])); 
	    
	if (isset($_POST['book_review_publisher']))
	    update_post_meta($post_id, 'book_review_publisher', sanitize_text_field($_POST['book_review_publisher'])); 
	    
	if (isset($_POST['book_review_release_date']))
	    update_post_meta($post_id, 'book_review_release_date', sanitize_text_field($_POST['book_review_release_date']));
	    
	if (isset($_POST['book_review_format']))
	    update_post_meta($post_id, 'book_review_format', sanitize_text_field($_POST['book_review_format']));
	    
	if (isset($_POST['book_review_pages']))
	    update_post_meta($post_id, 'book_review_pages', sanitize_text_field($_POST['book_review_pages'])); 
	    
	if (isset($_POST['book_review_source']))
	    update_post_meta($post_id, 'book_review_source', sanitize_text_field($_POST['book_review_source'])); 
	    
	if (isset($_POST['book_review_link1']))
	    update_post_meta($post_id, 'book_review_link1', esc_url_raw($_POST['book_review_link1']));
	    
	if (isset($_POST['book_review_link2']))
	    update_post_meta($post_id, 'book_review_link2', esc_url_raw($_POST['book_review_link2']));
	
	if (isset($_POST['book_review_link3']))
	    update_post_meta($post_id, 'book_review_link3', esc_url_raw($_POST['book_review_link3']));
	    
	if (isset($_POST['book_review_link4']))
	    update_post_meta($post_id, 'book_review_link4', esc_url_raw($_POST['book_review_link4']));
	    
	if (isset($_POST['book_review_link5']))
	    update_post_meta($post_id, 'book_review_link5', esc_url_raw($_POST['book_review_link5']));
	    
	if (isset($_POST['book_review_cover_url']))
	    update_post_meta($post_id, 'book_review_cover_url', esc_url_raw($_POST['book_review_cover_url'])); 
	    
	if (isset($_POST['book_review_summary']))
	    update_post_meta($post_id, 'book_review_summary', $_POST['book_review_summary']);
	    
	if (isset($_POST['book_review_rating']))
	    update_post_meta($post_id, 'book_review_rating', $_POST['book_review_rating']);
    }
}
?>