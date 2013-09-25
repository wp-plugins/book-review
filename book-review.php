<?php
/*
Plugin Name: Book Review
Plugin URI: http://bookwookie.ca/wordpress-book-review-plugin/
Version: 1.2
Description: Add book information such as title, author, publisher and cover photo to enhance your review posts.
Author: Donna Peplinskie
Author URI: http://bookwookie.ca
License: GPL v3

WordPress Book Review Plugin
Copyright (C) 2013, Donna Peplinskie - thebookwookie@gmail.com

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

require_once('book-review-meta-box.php');

class BookReview
{
    public function __construct() {
	wp_register_style('book-review-style', plugins_url('css/style.css', __FILE__));
	wp_enqueue_style('book-review-style');
	wp_enqueue_style('wp-color-picker');
	wp_enqueue_script('book-review', plugins_url('/js/book-review.js', __FILE__ ), array('jquery', 'wp-color-picker'));
	
	add_filter('the_content', array(&$this, 'add_book_review_info'));
	add_filter('the_excerpt', array(&$this, 'add_rating_to_home'));
	
	if (is_admin()) {
	    add_action('admin_menu', array(&$this, 'add_menu'));
	    add_action('admin_init', array(&$this, 'init_menu'));
	}
    }
    
    // Add entry in the settings menu.
    public function add_menu() {
	add_options_page('Book Review Settings', 'Book Review', 'manage_options', __FILE__, array(&$this, 'show_settings'));
    }
    
    // Print the menu page itself
    public function show_settings() {
	//General
	$general = get_option('book_review_general');
	$general_defaults = array(
	    'book_review_box_position' => 'top'
	);
	$general = wp_parse_args($general, $general_defaults);
	
	//Rating Images
	$ratings = get_option('book_review_ratings');
	$ratings_defaults = array(
	    'book_review_rating_default' => 1
	);
	$ratings = wp_parse_args($ratings, $ratings_defaults);
	
	//Links
	$links = get_option('book_review_links');
	
	//Tooltip
	$tooltip = '<img src="' . plugins_url('images/tooltip.gif', __FILE__ ) . '" />';
	?>
	<div class="wrap">
	    <?php
		screen_icon();
	    ?>
	    <h2>Book Review Settings</h2>
	    <!--<p>Some text describing what the plugin settings do.</p>-->
	    <div id="book_review_settings" class="postbox-container" style="width: 70%;">
		<form action="options.php" method="post">
		    <h3>General</h3>
		    <table class="form-table">
			<tbody>
			    <tr valign="top">
				<th scope="row">
				    <label>
					Review Box Position:&nbsp;&nbsp<a href="#" class="tooltip"><?php echo $tooltip ?><span>Whether to show the review box at the top or bottom of a post.</span></a>
				    </label>
				</th>
				<td>
				    <input id="book_review_box_position_top" type="radio" name="book_review_general[book_review_box_position]" value="top" <?php echo checked("top", $general['book_review_box_position'], false); ?>"><label for="book_review_box_position_top">Top</label>
				    <input id="book_review_box_position_bottom" type="radio" name="book_review_general[book_review_box_position]" value="bottom" <?php echo checked("bottom", $general['book_review_box_position'], false); ?>"><label for="book_review_box_position_bottom">Bottom</label>
				</td>
			    </tr>
			    <tr valign="top">
				<th scope="row">
				    <label for="book_review_bg_color">Review Box Background Color:</label>
				</th>
				<td>
				    <input id="book_review_bg_color" class="color-picker" type="text" name="book_review_general[book_review_bg_color]" value="<?php echo $general['book_review_bg_color']; ?>">
				</td>
			    </tr>
			    <tr valign="top">
				<th scope="row">
				    <label for="book_review_border_color">Review Box Border Color:</label>
				</th>
				<td>
				    <input id="book_review_border_color" class="color-picker" type="text" name="book_review_general[book_review_border_color]" value="<?php echo $general['book_review_border_color']; ?>">
				</td>
			    </tr>
			</tbody>
		    </table>
		    <h3>Rating Images</h3>
		    <p>Configure the images to use for displaying ratings.</p>
		    <table class="form-table">
			<tbody>
			    <tr valign="top">
				<th scope="row">
				    <label for="book_review_rating_home">
					Show rating on home page:&nbsp;&nbsp<a href="#" class="tooltip"><?php echo $tooltip ?><span>Whether to show the rating image on your home page when summary text is used.</span></a>
				    </label>
				</th>
				<td>
				    <input id="book_review_rating_home" type="checkbox" name="book_review_ratings[book_review_rating_home]" value="1" <?php echo checked(1, $ratings['book_review_rating_home'], false); ?>">
				</td>
			    </tr>
			    <tr valign="top">
				<th scope="row">
				    <label for="book_review_rating_default">
					Use default rating images:&nbsp;&nbsp<a href="#" class="tooltip"><?php echo $tooltip ?><span>Whether to use the default rating images or your own.</span></a>
				    </label>
				</th>
				<td>
				    <input id="book_review_rating_default" type="checkbox" name="book_review_ratings[book_review_rating_default]" value="1" <?php echo checked(1, $ratings['book_review_rating_default'], false); ?> onchange="showRatingImages();">
				</td>
			    </tr>								
			    <tr class="rating">
				<th scope="row">
				    <h4>
					<label>
					    Rating Image URLs&nbsp;&nbsp<a href="#" class="tooltip"><?php echo $tooltip ?><span>To use your own rating images, enter the URL of an image for each rating below (1-5).</span></a>
					</label>					
				    </h4>
				</th>
				<td></td>
			    </tr>
			    <?php
				for ($i = 1; $i <= 5; $i++) { ?>
				    <tr class="rating" valign="top">
					<th scope="row">
					    <label for="<?php echo 'book_review_rating_image' . $i; ?>">
						<?php
						    if ($i == 1) {
							$stars = 'One';
						    }
						    else if ($i == 2) {
							$stars = 'Two';
						    }
						    else if ($i == 3) {
							$stars = 'Three';
						    }
						    else if ($i == 4) {
							$stars = 'Four';
						    }
						    else if ($i == 5) {
							$stars = 'Five';
						    }
						    
						    $this->create_rating_image_url_label(array('stars' => $stars));
						?>
					    </label>
					</th>
					<td>
					    <?php $this->create_rating_image_url_field(array('id' => 'book_review_rating_image' . $i)); ?>								
					</td>
				    </tr>
			    <?php } ?>
			</tbody>
		    </table>						    
		    <h3>Links</h3>
		    <p>Configure the links that you would like to display with every book review.</p>
		    <table class="custom-links form-table">
			<tbody>
			    <tr valign="top">
				<th scope="row">
				    <label for="book_review_num_links">
					Number of Links:&nbsp;&nbsp<a href="#" class="tooltip"><?php echo $tooltip ?><span>Select the number of links you would like to add to each book review.</span></a>
				    </label>
				</th>
				<td>
				    <?php $this->create_num_links_field(); ?>
				</td>
			    </tr>
			    <tr valign="top">
				<th scope="row">
				    <label for="book_review_link_target">
					Open links in new tab:&nbsp;&nbsp<a href="#" class="tooltip"><?php echo $tooltip ?><span>Whether to open links in the same window or in a new tab.</span></a>
				    </label>
				</th>
				<td>
				    <?php $this->create_link_target_field(); ?>					
				</td>
			    </tr>
			</tbody>
		    </table>
		    <table class="links widefat">
			<thead>
			    <tr>
				<th>
				    <label>
					Link Text&nbsp;&nbsp<a href="#" class="tooltip"><?php echo $tooltip ?><span>Enter the text for each link. For every link added here,
					a new field will be shown in the Book Info section when editing a post.</span></a>
				    </label>
				</th>
				<th>
				    <label>
					Link Image URL&nbsp;&nbsp<a href="#" class="tooltip"><?php echo $tooltip ?><span>If you would like to show links as images,
					enter the URL of an image for each link below. If you leave this field blank, links will be shown as text.</span></a>
				    </label>				    
				</th>       
			    </tr>
			</thead>
			<tbody>
			<?php
			    for ($i = 1; $i <= 5; $i++) { ?>
				<tr id="<?php echo 'link' . $i; ?>">
				  <td><?php $this -> create_link_text_field(array('id' => 'book_review_link_text' . $i, 'value' => $i, 'label_for' => 'book_review_link_text' . $i)); ?></td>
				  <td><?php $this -> create_link_image_field(array('id' => 'book_review_link_image' . $i, 'value' => $i, 'label_for' => 'book_review_link_image' . $i)); ?></td>
				</tr>
			<?php } ?>	
			</tbody>
		    </table>			
		    <script type="text/javascript">
			showRatingImages();
			showLinks();
		    </script>
		    <?php
			// Output the hidden fields, nonce etc. This is a group.
			settings_fields('book_review_options');
			submit_button();
		    ?>
		</form>
	    </div>
	</div>
	<?php
    }
    
    public function init_menu() {		
	//Register settings so that they are saved.
	register_setting('book_review_options', 'book_review_general');
	register_setting('book_review_options', 'book_review_ratings', array($this, 'validate_rating_images'));
	register_setting('book_review_options', 'book_review_links', array($this, 'validate_links'));
    }
    
    public function create_rating_image_url_label($args) {
	echo $args['stars'] . '-star Image URL:';
    }
    
    public function create_rating_image_url_field($args) {
	$options = get_option('book_review_ratings');
	
	echo '<input id="' . $args["id"] .'" type="text" name="book_review_ratings[' . $args["id"] . ']" class="text-input" value="' . esc_url($options[$args["id"]]) . '" />';	
    }
    
    public function create_num_links_field() {
	$options = get_option('book_review_links');
	$items = array("0" => "None", "1" => "1", "2" => "2", "3" => "3", "4" => "4", "5" => "5");
	echo '<select id="book_review_num_links" name="book_review_links[book_review_num_links]" onChange="showLinks(parseInt(this.value));">';
	
	foreach($items as $type => $item) {
	    $selected = ($options["book_review_num_links"] == $type) ? 'selected="selected"' : '';
	    echo "<option value='" . $type . "' " . $selected . ">" . $item . "</option>";
	}
	
	echo "</select>";
    }
    
    public function create_link_target_field() {
	$options = get_option('book_review_links');
	
	echo '<input id="book_review_link_target" type="checkbox" name="book_review_links[book_review_link_target]" value="1" ' . checked(1, $options['book_review_link_target'], false) . '/>';   
    }
    
    public function create_link_text_field($args) {
	$options = get_option('book_review_links');
		
	echo '<input id="' . $args["id"] .'" type="text" name="book_review_links[' . $args["id"] . ']" class="text-input" value="' . $options[$args["id"]] . '" />';
    }
    
    public function create_link_image_field($args) {
	$options = get_option('book_review_links');
	
	echo '<input id="' . $args["id"] .'" type="text" name="book_review_links[' . $args["id"] . ']" class="text-input" value="' . esc_url($options[$args["id"]]) . '" />';    
    }
    
    public function validate_rating_images($input) {
	$output = array();
	$image_error = false;
	
	$output['book_review_rating_home'] = $input['book_review_rating_home'];
	$output['book_review_rating_default'] = $input['book_review_rating_default'];
		
	for ($i = 1; $i <= 5; $i++) {
	    $value = trim($input['book_review_rating_image' . $i]);
	    
	    if ($input['book_review_rating_default'] == "") {				
		if (empty($value)) {
		    $image_error = true;
		}
		else {
		    $output['book_review_rating_image' . $i] = esc_url_raw($value);
		}
	    }
	    else {
		$output['book_review_rating_image' . $i] = esc_url_raw($value);
	    }
	}
	
	if ($image_error) {
	    add_settings_error(
		'book_review_ratings',
		'image-error',
		'Rating Image URLs are required fields when not using the default rating images. Please ensure you enter a URL for each rating.',
		'error'
	    );
	}
	
	return $output;
    }
    
    public function validate_links($input) {
	$output = array();
	$link_error = false;
	
	$output['book_review_num_links'] = $input['book_review_num_links'];
	$output['book_review_link_target'] = $input['book_review_link_target'];
		
	for ($i = 1; $i <= (int)$input['book_review_num_links']; $i++) {
	    $text = trim($input['book_review_link_text' . $i]);
	    $output['book_review_link_image' . $i] = esc_url_raw(trim($input['book_review_link_image' . $i]));
	    
	    if (empty($text)) {
		$link_error = true;
	    }
	    else {
		$output['book_review_link_text' . $i] = sanitize_text_field($text);
	    }
	}
	
	if ($link_error) {
	    add_settings_error(
		'book_review_links',
		'link-error',
		'Link Text is a required field. Please ensure you either enter text for each link or decrease the number of links you want to show.',
		'error'
	    );
	}
	
	return $output;
    }
    
    //Render the book review info for a post.
    public function add_book_review_info($content) {
	if (is_home() || is_single() || is_feed()) {
	    $values = get_post_custom();
	    
	    //General
	    $general = get_option('book_review_general');
	    $general_defaults = array(
		'book_review_box_position' => 'top'
	    );
	    $general = wp_parse_args($general, $general_defaults);
	    
	    //Rating Images
	    $ratings = get_option('book_review_ratings');
	    $ratings_defaults = array(
		'book_review_rating_default' => 1
	    );
	    $ratings = wp_parse_args($ratings, $ratings_defaults);
	
	    $options = get_option('book_review_links');
	    $num_links = $options['book_review_num_links'];
	    $link_target = $options['book_review_link_target'];
	    
	    //Title must be specified.
	    if ($values["book_review_title"][0] == null) {
		//Do nothing.
	    }
	    else
	    {		
		$title = $values["book_review_title"][0];
		$series = $values["book_review_series"][0];
		$author = $values["book_review_author"][0];
		$genre = $values["book_review_genre"][0];
		$publisher = $values["book_review_publisher"][0];
		$release_date = $values["book_review_release_date"][0];
		$format = $values["book_review_format"][0];
		$pages = $values["book_review_pages"][0];
		$source = $values["book_review_source"][0];	
		$link1 = esc_url($values["book_review_link1"][0]);
		$link2 = esc_url($values["book_review_link2"][0]);
		$link3 = esc_url($values["book_review_link3"][0]);
		$link4 = esc_url($values["book_review_link4"][0]);
		$link5 = esc_url($values["book_review_link5"][0]);
		$cover_url = esc_url($values["book_review_cover_url"][0]);
		$summary = $values["book_review_summary"][0];
		$rating = $values["book_review_rating"][0];
		$box_position = $general["book_review_box_position"];
		$bg_color = $general["book_review_bg_color"];
		$border_color = $general["book_review_border_color"];
		
		//Don't apply inline CSS to an RSS feed.
		if (is_feed()) {
		    $bg_style = '';
		}
		else {
		    if (isset($bg_color) && !empty($bg_color)) {
			$bg_style = 'style="background-color: ' . $bg_color . ';';
		    }
		    
		    if (isset($border_color) && !empty($border_color)) {
			if (isset($bg_style)) {
			    $bg_style .= ' border: 1px solid ' . $border_color . ';"';
			}
			else {
			    $bg_style = 'style="border: 1px solid ' . $border_color . ';"';
			}
		    }
		    else {
			if (isset($bg_style)) {
			    $bg_style .= '"';
			}
		    }
		}
		
		$book_info = '<div id="book-review"' . $bg_style . '>';
		
		if (!empty($cover_url)) {
		    $book_info .= '<img id="book_review_cover_image" src="' . $cover_url . '" alt="' . $title . ' Book Cover ' . '" />';
		}
		
		if (!empty($title)) {
		    $book_info .= '<label for="book_review_title">Title:</label>';
		    $book_info .= '<span id="book_review_title">' . $title . '</span><br />';
		}
		
		if (!empty($series)) {
		    $book_info .= '<label for="book_review_series">Series:</label>';
		    $book_info .= '<span id="book_review_series">' . $series . '</span><br />';
		}
		
		if (!empty($author)) {
		    $book_info .= '<label for="book_review_author">Author:</label>';
		    $book_info .= '<span id="book_review_author">' . $author . '</span><br />';
		}
		
		if (!empty($genre)) {
		    $book_info .= '<label for="book_review_genre">Genre:</label>';
		    $book_info .= '<span id="book_review_genre">' . $genre . '</span><br />';
		}
		
		if (!empty($publisher)) {
		    $book_info .= '<label for="book_review_publisher">Publisher:</label>';
		    $book_info .= '<span id="book_review_publisher">' . $publisher . '</span><br />';
		}
		
		if (!empty($release_date)) {
		    $book_info .= '<label for="book_review_release_date">Release Date:</label>';
		    $book_info .= '<span id="book_review_release_date">' . $release_date . '</span><br />';
		}
		
		if (!empty($format)) {
		    $book_info .= '<label for="book_review_format">Format:</label>';
		    $book_info .= '<span id="book_review_format">' . $format . '</span><br />';
		}
		
		if (!empty($pages)) {
		    $book_info .= '<label for="book_review_pages">Pages:</label>';
		    $book_info .= '<span id="book_review_pages">' . $pages . '</span><br />';
		}
		
		if (!empty($source)) {
		    $book_info .= '<label for="book_review_source">Source:</label>';
		    $book_info .= '<span id="book_review_source">' . $source . '</span><br />';
		}
		
		if (!empty($rating) && ($rating != -1)) {
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
			$src = $ratings['book_review_rating_image' . $rating];
		    }
		    
		    $book_info .= '<img id="book_review_rating_image" src="' . $src . '"/><br />';
		}
		
		if (!empty($summary)) {
		    $book_info .= '<br />';
		    $book_info .= '<label for="book_review_summary">Summary:</label>';
		    $book_info .= '<div id="book_review_summary">' . wpautop($summary, true) . '</div>';
		}
		
		$book_info .= '<ul id="book-review-links">';
		
		if ($link_target == 1) {
		    $target = 'target=_blank';
		}
		else {
		    $target = '';
		}
		
		if (!empty($link1) && ($num_links >= 1)) {
		    $book_info .= '<li>';
		    
		    if ($options['book_review_link_image1']) {
			$book_info .= '<a class="custom-link" href="' . $link1 . '" ' . $target . '>' .
			    '<img src="' . $options['book_review_link_image1'] . '" alt="' . $options['book_review_link_text1'] . '" />' . '</a>';
		    }
		    else {
			$book_info .= '<a class="custom-link" href="' . $link1 . '" ' . $target . '>' . $options['book_review_link_text1'] . '</a>';
		    }
		    
		    $book_info .= '</li>';
		}
		
		if (!empty($link2) && ($num_links >= 2)) {
		    $book_info .= '<li>';
		    
		    if ($options['book_review_link_image2']) {
			$book_info .= '<a class="custom-link" href="' . $link2 . '" ' . $target . '>' .
			    '<img src="' . $options['book_review_link_image2'] . '" alt="' . $options['book_review_link_text2'] . '" />' . '</a>';
		    }
		    else {
			$book_info .= '<a class="custom-link" href="' . $link2 . '" ' . $target . '>' . $options['book_review_link_text2'] . '</a>';
		    }
		    		    
		    $book_info .= '</li>';
		}
		
		if (!empty($link3) && ($num_links >= 3)) {
		    $book_info .= '<li>';
		    
		    if ($options['book_review_link_image3']) {
			$book_info .= '<a class="custom-link" href="' . $link3 . '" ' . $target . '>' .
			    '<img src="' . $options['book_review_link_image3'] . '" alt="' . $options['book_review_link_text3'] . '" />' . '</a>';
		    }
		    else {
			$book_info .= '<a class="custom-link" href="' . $link3 . '" ' . $target . '>' . $options['book_review_link_text3'] . '</a>';
		    }
		    
		    $book_info .= '</li>';
		}
		
		if (!empty($link4) && ($num_links >= 4)) {
		    $book_info .= '<li>';
		    
		    if ($options['book_review_link_image4']) {
			$book_info .= '<a class="custom-link" href="' . $link4 . '" ' . $target . '>' .
			    '<img src="' . $options['book_review_link_image4'] . '" alt="' . $options['book_review_link_text4'] . '" />' . '</a>';
		    }
		    else {
			$book_info .= '<a class="custom-link" href="' . $link4 . '" ' . $target . '>' . $options['book_review_link_text4'] . '</a>';
		    }
		    
		    $book_info .= '</li>';
		}
		
		if (!empty($link5) && ($num_links >= 5)) {
		    $book_info .= '<li>';
		    
		    if ($options['book_review_link_image5']) {
			$book_info .= '<a class="custom-link" href="' . $link5 . '" ' . $target . '>' .
			    '<img src="' . $options['book_review_link_image5'] . '" alt="' . $options['book_review_link_text5'] . '" />' . '</a>';
		    }
		    else {
			$book_info .= '<a class="custom-link" href="' . $link5 . '" ' . $target . '>' . $options['book_review_link_text5'] . '</a>';
		    }
		    
		    $book_info .= '</li>';
		}
		
		$book_info .= '</ul>';
		$book_info .= '</div>';
		
		if ($box_position == "top") {
		    $content = $book_info . $content;
		}
		else {	//Bottom
		    $content = $content . $book_info;
		}
	    }
	}
	
	return $content;
    }
    
    public function add_rating_to_home($content) {
	if (is_home() || is_archive() || is_search()) {
	    $values = get_post_custom(get_the_ID());
	    $ratings = get_option('book_review_ratings');
	    
	    if ((isset($ratings['book_review_rating_home']) != null) && (isset($values["book_review_rating"]) != null)) {	
		$rating = $values["book_review_rating"][0];
	    
		if ($ratings['book_review_rating_home'] == "1") {
		    if (!empty($rating) && ($rating != "-1")) {
			if ($ratings['book_review_rating_default'] == "1") {
			    if ($rating == "1") {
				$src = plugins_url('images/one-star.png', __FILE__);
			    }
			    else if ($rating == "2") {
				$src = plugins_url('images/two-star.png', __FILE__);
			    }
			    else if ($rating == "3") {
				$src = plugins_url('images/three-star.png', __FILE__);
			    }
			    else if ($rating == "4") {
				$src = plugins_url('images/four-star.png', __FILE__);
			    }
			    else if ($rating == "5") {
				$src = plugins_url('images/five-star.png', __FILE__);
			    }			
			}
			else {
			    $src = $ratings['book_review_rating_image' . $rating];
			}
			
			$content = '<p class="book_review_rating_image"><img src="' . $src . '"/>' . $content . '</p>';
		    }
		}
	    }
	}
	
	return $content;
    }
}

$bookReview = new BookReview();
?>