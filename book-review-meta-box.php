<?php
/**
 * Calls the class on the post edit screen.
 */
function call_BookReviewAdmin() {
	return new BookReviewAdmin();
}

if (is_admin()) {
	add_action('load-post.php', 'call_BookReviewAdmin');
	add_action('load-post-new.php', 'call_BookReviewAdmin');
}

class BookReviewAdmin {
	public function __construct() {
		add_action('save_post', array(&$this, 'book_review_save_meta_box'));
		add_action('add_meta_boxes', array(&$this, 'book_review_add_meta_box'));
	}

	/**
	 * Adds the meta box container to posts and custom post types.
	 */
	public function book_review_add_meta_box() {
		$post_types = get_post_types();

		foreach ( $post_types as $post_type ) {
			if ( ($post_type != 'page') && ($post_type != 'attachment') && 
				($post_type != 'revision') && ($post_type != 'nav_menu_item') ) {
				add_meta_box( 
					'book-review-meta-box',
					'Book Info',
					array(&$this, 'book_review_render_meta_box'),
					$post_type,
					'normal',
					'high'
				);
			}
		}
	}

	/**
	 * Callback function to show fields in meta box.
	 */
	public function book_review_render_meta_box($post) {	
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
		$archive_post = isset($values['book_review_archive_post']) ? $values['book_review_archive_post'][0] : 1;
		$args = array(
			"textarea_rows" => 15,
			"media_buttons" => false
		);
		
		//We'll use this nonce field later on when saving.  
		wp_nonce_field('save_meta_box_nonce', 'book-review-meta-box-nonce'); ?>
		
		<label for="book_review_title"><?php _e('Title', 'book-review') ?>:<span class="required">*</span></label>
		<input type="text" id="book_review_title" name="book_review_title" value="<?php echo $title; ?>" />
		<br />
		<label for="book_review_series"><?php _e('Series', 'book-review') ?>:</label>
		<input type="text" id="book_review_series" name="book_review_series" value="<?php echo $series; ?>" />
		<br />
		<label for="book_review_author"><?php _e('Author', 'book-review') ?>:</label>
		<input type="text" id="book_review_author" name="book_review_author" value="<?php echo $author; ?>" />
		<br />
		<label for="book_review_genre"><?php _e('Genre', 'book-review') ?>:</label>
		<input type="text" id="book_review_genre" name="book_review_genre" value="<?php echo $genre; ?>" />
		<br />
		<label for="book_review_publisher"><?php _e('Publisher', 'book-review') ?>:</label>
		<input type="text" id="book_review_publisher" name="book_review_publisher" value="<?php echo $publisher; ?>" />
		<br />
		<label for="book_review_release_date"><?php _e('Release Date', 'book-review') ?>:</label>
		<input type="text" id="book_review_release_date" name="book_review_release_date" value="<?php echo $release_date; ?>" />
		<br />
		<label for="book_review_format"><?php _e('Format', 'book-review') ?>:</label>
		<input type="text" id="book_review_format" name="book_review_format" value="<?php echo $format; ?>" />
		<br />
		<label for="book_review_pages"><?php _e('Pages', 'book-review') ?>:</label>
		<input type="text" id="book_review_pages" name="book_review_pages" value="<?php echo $pages; ?>" />
		<br />
		<label for="book_review_source"><?php _e('Source', 'book-review') ?>:</label>
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
		
		<label for="book_review_cover_url"><?php _e('Cover URL', 'book-review') ?>:</label>
		<input id="book_review_cover_url" name="book_review_cover_url" type="text" value="<?php echo $cover_url; ?>" readonly />
		<a href="#" class="button button-small upload-image-button"><?php _e('Upload Cover', 'book-review') ?></a>
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
		<label for="book_review_summary" class="summary"><?php _e('Synopsis', 'book-review') ?>:</label>
		<?php wp_editor($summary, "book_review_summary", $args); ?>
		<br />
		<label for="book_review_rating"><?php _e('Rating', 'book-review') ?>:</label>	
		<select id="book_review_rating" name="book_review_rating">
			<?php
				$items = array("-1" => __('Select...', 'book-review'), "1" => "1", "2" => "2", "3" => "3", "4" => "4", "5" => "5");
				
				foreach ($items as $type => $item) {
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
		<label for="book_review_archive_post"><?php _e('Include post in archives', 'book-review') ?>:</label>
		<input id="book_review_archive_post" type="checkbox" name="book_review_archive_post" value="1" <?php echo checked(1, $archive_post, false) ?> />
		<br />	
		<?php
	}
	
	public function book_review_save_meta_box($post_id) {
	//Bail if we're doing an auto save.
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
		
		//If our nonce isn't there, or we can't verify it, bail.
		if (!isset($_POST['book-review-meta-box-nonce']) || !wp_verify_nonce($_POST['book-review-meta-box-nonce'], 'save_meta_box_nonce')) return;
		
		//If our current user can't edit this post, bail.
		if (!current_user_can('edit_post')) return;
		
		//Make sure data is set before trying to save it.
		if (isset($_POST['book_review_title'])) {
			update_post_meta($post_id, 'book_review_title', sanitize_text_field($_POST['book_review_title']));
			update_post_meta($post_id, 'book_review_archive_title', $this->get_archive_title());
		}
		
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
			
		update_post_meta($post_id, 'book_review_archive_post', $_POST['book_review_archive_post']);
	}
	
	//Move common stopwords to end of Title.
	private function get_archive_title() {
		$title = trim($_POST['book_review_title']);
		$stopwords = array(__('the', 'book-review'), __('a', 'book-review'), __('an', 'book-review'));
		/* Translations may specify multiple stopwords for each English word. Separate them into a comma-delimited list
		   in order to avoid a multi-dimensional array. */
		$stopwords = implode(',', $stopwords);
		//Now put them back into a one-dimensional array.
		$stopwords = explode(',', $stopwords);
	
		foreach ($stopwords as $stopword) {
			$stopword = trim($stopword);
			
			/* Check if first characters of the title is a stop word. Add a space at the end of the stopword so that
			   only full words are matched. */
			$substring = substr($title, 0, strlen($stopword) + 1);
			
			//Move stopword to the end if a match is found.
			if (strtolower($substring) == ($stopword . ' ')) {
				return sanitize_text_field(substr($title, strlen($stopword) + 1) . ', ' . $substring);
			}
		}
		
		return sanitize_text_field($_POST['book_review_title']);
	}
}
?>