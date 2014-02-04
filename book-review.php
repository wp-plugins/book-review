<?php
/*
Plugin Name: Book Review
Plugin URI: http://donnapeplinskie.com/wordpress-book-review-plugin/
Version: 1.8
Description: Add book information such as title, author, publisher and cover photo to enhance your review posts.
Author: Donna Peplinskie
Author URI: http://donnapeplinskie.com
License: GPL v3

WordPress Book Review Plugin
Copyright (C) 2013, Donna Peplinskie - donnapep@gmail.com

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
		add_shortcode('book_review_archives', array(&$this, 'handle_shortcode'));
		
		if (is_admin()) {
		    add_action('admin_menu', array(&$this, 'add_menu'));
		    add_action('admin_init', array(&$this, 'init_menu'));
		}
		
		add_action('init', array(&$this, 'load_plugin_textdomain'));
    }
    
    public function load_plugin_textdomain() {
		load_plugin_textdomain('book-review', false, dirname(plugin_basename( __FILE__ )) . '/languages');
    }
    
    //Add entry in the settings menu.
    public function add_menu() {
		add_options_page('Book Review Settings', 'Book Review', 'manage_options', __FILE__, array(&$this, 'show_settings'));
    }
    
    //Print the menu page itself.
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
		$tooltip = '<img src="' . plugins_url('images/tooltip.gif', __FILE__ ) . '" />'; ?>
		
		<div class="wrap">
		    <?php screen_icon(); ?>
		    
		    <h2>Book Review Settings</h2>
		    <!--<p>Some text describing what the plugin settings do.</p>-->
		    <div id="book_review_settings" class="postbox-container" style="width: 70%;">
				<form action="options.php" method="post">
				    <h3><?php _e('General', 'book-review') ?></h3>
				    <table class="form-table">
						<tbody>
						    <tr valign="top">
								<th scope="row">
								    <?php _e('Review Box Position', 'book-review') ?>:&nbsp;&nbsp
									<a href="#" class="tooltip">
										<?php echo $tooltip ?>
									    <span><?php _e('Whether to show the review box at the top or bottom of a post.', 'book-review') ?></span>
									</a>
								</th>
								<td>
								    <input id="book_review_box_position_top" type="radio" name="book_review_general[book_review_box_position]" value="top" <?php echo checked("top", $general['book_review_box_position'], false); ?>"><label for="book_review_box_position_top"><?php _e('Top', 'book-review') ?></label>
								    <input id="book_review_box_position_bottom" type="radio" name="book_review_general[book_review_box_position]" value="bottom" <?php echo checked("bottom", $general['book_review_box_position'], false); ?>"><label for="book_review_box_position_bottom"><?php _e('Bottom', 'book-review') ?></label>
								</td>
						    </tr>
						    <tr valign="top">
								<th scope="row">
								    <label for="book_review_bg_color"><?php _e('Review Box Background Color', 'book-review') ?>:</label>
								</th>
								<td>
								    <input id="book_review_bg_color" class="color-picker" type="text" name="book_review_general[book_review_bg_color]" value="<?php echo $general['book_review_bg_color']; ?>">
								</td>
						    </tr>
						    <tr valign="top">
								<th scope="row">
								    <label for="book_review_border_color"><?php _e('Review Box Border Color', 'book-review') ?>:</label>
								</th>
								<td>
								    <input id="book_review_border_color" class="color-picker" type="text" name="book_review_general[book_review_border_color]" value="<?php echo $general['book_review_border_color']; ?>">
								</td>
						    </tr>
						</tbody>
				    </table>
				    <h3><?php _e('Rating Images', 'book-review') ?></h3>
				    <p><?php _e('Configure the images to use for displaying ratings.', 'book-review') ?></p>
				    <table class="form-table">
						<tbody>
						    <tr valign="top">
								<th scope="row">
								    <label for="book_review_rating_home">
										<?php _e('Show rating on home page', 'book-review') ?>:&nbsp;&nbsp
										<a href="#" class="tooltip">
											<?php echo $tooltip ?>
										    <span><?php _e('Whether to show the rating image on your home page when summary text is used.', 'book-review') ?></span>
										</a>
								    </label>
								</th>
								<td>
								    <input id="book_review_rating_home" type="checkbox" name="book_review_ratings[book_review_rating_home]" value="1" <?php echo checked(1, $ratings['book_review_rating_home'], false); ?>">
								</td>
						    </tr>
						    <tr valign="top">
								<th scope="row">
								    <label for="book_review_rating_default">
										<?php _e('Use default rating images', 'book-review') ?>:&nbsp;&nbsp
										<a href="#" class="tooltip">
											<?php echo $tooltip ?>
										    <span><?php _e('Whether to use the default rating images or your own.', 'book-review') ?></span>
										</a>
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
										    <?php _e('Rating Image URLs', 'book-review') ?>&nbsp;&nbsp
										    <a href="#" class="tooltip">
										    	<?php echo $tooltip ?>
												<span><?php _e('To use your own rating images, enter the URL of an image for each rating below (1-5).', 'book-review') ?></span>
										    </a>
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
														_e('One-star Image URL', 'book-review');
														echo ':';
													}
													else if ($i == 2) {
														_e('Two-star Image URL', 'book-review');
														echo ':';
													}
													else if ($i == 3) {
														_e('Three-star Image URL', 'book-review');
														echo ':';
													}
													else if ($i == 4) {
														_e('Four-star Image URL', 'book-review');
														echo ':';
													}
													else if ($i == 5) {
														_e('Five-star Image URL', 'book-review');
														echo ':';
													}
												?>
										    </label>
										</th>
										<td>
										    <?php $this -> create_rating_image_url_field(array('id' => 'book_review_rating_image' . $i)); ?>								
										</td>
								    </tr>
						    <?php } ?>
						</tbody>
				    </table>
				    <h3><?php _e('Links', 'book-review') ?></h3>
				    <p><?php _e('Configure the links that you would like to display with every book review.', 'book-review') ?></p>
				    <table class="custom-links form-table">
						<tbody>
						    <tr valign="top">
								<th scope="row">
								    <label for="book_review_num_links">
										<?php _e('Number of Links', 'book-review') ?>:&nbsp;&nbsp
										<a href="#" class="tooltip">
											<?php echo $tooltip ?>
										    <span><?php _e('Select the number of links you would like to add to each book review.', 'book-review') ?></span>
										</a>
								    </label>
								</th>
								<td>
								    <?php $this -> create_num_links_field(); ?>
								</td>
						    </tr>
						    <tr valign="top">
								<th scope="row">
								    <label for="book_review_link_target">
										<?php _e('Open links in new tab', 'book-review') ?>:&nbsp;&nbsp
										<a href="#" class="tooltip">
											<?php echo $tooltip ?>
										    <span><?php _e('Whether to open links in the same window or in a new tab.', 'book-review') ?></span>
										</a>
								    </label>
								</th>
								<td>
								    <?php $this -> create_link_target_field(); ?>					
								</td>
						    </tr>
						</tbody>
				    </table>
				    <table class="links widefat">
						<thead>
						    <tr>
								<th>
								    <label>
										<?php _e('Link Text', 'book-review') ?>&nbsp;&nbsp
										<a href="#" class="tooltip">
											<?php echo $tooltip ?>
										    <span><?php _e('Enter the text for each link. For every link added here, a new field will be shown in the Book Info section when editing a post.', 'book-review') ?></span>
										</a>
								    </label>
								</th>
								<th>
								    <label>
										<?php _e('Link Image URL', 'book-review') ?>&nbsp;&nbsp
										<a href="#" class="tooltip">
											<?php echo $tooltip ?>
										    <span><?php _e('If you would like to show links as images, enter the URL of an image for each link below. If you leave this field blank, links will be shown as text.', 'book-review') ?></span>
										</a>
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

	private function create_rating_image_url_field($args) {
		$options = get_option('book_review_ratings');
	
		echo '<input id="' . $args["id"] .'" type="text" name="book_review_ratings[' . $args["id"] . ']" class="text-input" value="' . esc_url($options[$args["id"]]) . '" />';
	}

	private function create_num_links_field() {
		$options = get_option('book_review_links');
		$items = array("0" => "None", "1" => "1", "2" => "2", "3" => "3", "4" => "4", "5" => "5");
		echo '<select id="book_review_num_links" name="book_review_links[book_review_num_links]" onChange="showLinks(parseInt(this.value));">';
	
		foreach($items as $type => $item) {
			$selected = ($options["book_review_num_links"] == $type) ? 'selected="selected"' : '';
			echo "<option value='" . $type . "' " . $selected . ">" . $item . "</option>";
		}
	
		echo "</select>";
	}

	private function create_link_target_field() {
		$options = get_option('book_review_links');
	
		echo '<input id="book_review_link_target" type="checkbox" name="book_review_links[book_review_link_target]" value="1" ' . checked(1, $options['book_review_link_target'], false) . '/>';
	}

	private function create_link_text_field($args) {
		$options = get_option('book_review_links');
	
		echo '<input id="' . $args["id"] .'" type="text" name="book_review_links[' . $args["id"] . ']" class="text-input" value="' . $options[$args["id"]] . '" />';
	}

	private function create_link_image_field($args) {
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
			else {
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
					$book_info .= '<label for="book_review_title">' . __('Title', 'book-review') . ':</label>';
					$book_info .= '<span id="book_review_title">' . $title . '</span><br />';
				}
			
				if (!empty($series)) {
					$book_info .= '<label for="book_review_series">' . __('Series', 'book-review') . ':</label>';
					$book_info .= '<span id="book_review_series">' . $series . '</span><br />';
				}
			
				if (!empty($author)) {
					$book_info .= '<label for="book_review_author">' . __('Author', 'book-review') . ':</label>';
					$book_info .= '<span id="book_review_author">' . $author . '</span><br />';
				}
			
				if (!empty($genre)) {
					$book_info .= '<label for="book_review_genre">' . __('Genre', 'book-review') . ':</label>';
					$book_info .= '<span id="book_review_genre">' . $genre . '</span><br />';
				}
			
				if (!empty($publisher)) {
					$book_info .= '<label for="book_review_publisher">' . __('Publisher', 'book-review') . ':</label>';
					$book_info .= '<span id="book_review_publisher">' . $publisher . '</span><br />';
				}
			
				if (!empty($release_date)) {
					$book_info .= '<label for="book_review_release_date">' . __('Release Date', 'book-review') . ':</label>';
					$book_info .= '<span id="book_review_release_date">' . $release_date . '</span><br />';
				}
			
				if (!empty($format)) {
					$book_info .= '<label for="book_review_format">' . __('Format', 'book-review') . ':</label>';
					$book_info .= '<span id="book_review_format">' . $format . '</span><br />';
				}
			
				if (!empty($pages)) {
					$book_info .= '<label for="book_review_pages">' . __('Pages', 'book-review') . ':</label>';
					$book_info .= '<span id="book_review_pages">' . $pages . '</span><br />';
				}
			
				if (!empty($source)) {
					$book_info .= '<label for="book_review_source">' . __('Source', 'book-review') . ':</label>';
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
				
					$book_info .= '<img id="book_review_rating_image" src="' . $src . '" /><br />';
				}
			
				if (!empty($summary)) {
					$book_info .= '<br />';
					$book_info .= '<label for="book_review_summary">' . __('Synopsis', 'book-review') . ':</label>';
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
					
						$content = '<p class="book_review_rating_image"><img src="' . $src . '" />' . $content . '</p>';
					}
				}
			}
		}
	
		return $content;
	}

	public function handle_shortcode($atts) {
		global $wpdb;
	
		$prefix = $wpdb->prefix;
	
		extract(shortcode_atts(array(
			'type' => 'title',
			'show_cover' => 'false',
			'show_rating' => 'false',
		), $atts));
	
		if ($type == 'title') {
			$query = "
				SELECT DISTINCT title.post_id, thumb.meta_value AS thumb, IFNULL(archive.meta_value, 1) AS archivePost, IFNULL(archiveTitle.meta_value, title.meta_value) AS title, author.meta_value AS author
				FROM {$prefix}posts wp
				INNER JOIN {$prefix}postmeta title ON wp.ID = title.post_id
				LEFT OUTER JOIN {$prefix}postmeta thumb ON wp.ID = thumb.post_id AND thumb.meta_key = '_thumbnail_id'
				LEFT OUTER JOIN {$prefix}postmeta archiveTitle ON title.post_id = archiveTitle.post_id AND archiveTitle.meta_key = 'book_review_archive_title'
				LEFT OUTER JOIN {$prefix}postmeta author ON title.post_id = author.post_id AND author.meta_key = 'book_review_author'
				LEFT OUTER JOIN {$prefix}postmeta archive ON title.post_id = archive.post_id AND archive.meta_key = 'book_review_archive_post'
				WHERE title.meta_key = 'book_review_title' AND title.meta_value <> '' AND wp.post_status =  'publish'
				ORDER BY title";
		}
		else if ($type == 'genre') {
			$query = "
				SELECT DISTINCT genre.post_id, thumb.meta_value AS thumb, IFNULL(archive.meta_value, 1) AS archivePost, IFNULL(archiveTitle.meta_value, title.meta_value) AS title, author.meta_value AS author, genre.meta_value AS genre
				FROM {$prefix}posts wp
				INNER JOIN {$prefix}postmeta genre ON wp.ID = genre.post_id
				LEFT OUTER JOIN {$prefix}postmeta thumb ON wp.ID = thumb.post_id AND thumb.meta_key = '_thumbnail_id'
				LEFT OUTER JOIN {$prefix}postmeta title ON genre.post_id = title.post_id AND title.meta_key = 'book_review_title'
				LEFT OUTER JOIN {$prefix}postmeta archiveTitle ON genre.post_id = archiveTitle.post_id AND archiveTitle.meta_key = 'book_review_archive_title'
				LEFT OUTER JOIN {$prefix}postmeta author ON genre.post_id = author.post_id AND author.meta_key = 'book_review_author'
				LEFT OUTER JOIN {$prefix}postmeta archive ON genre.post_id = archive.post_id AND archive.meta_key = 'book_review_archive_post'
				WHERE genre.meta_key = 'book_review_genre' AND genre.meta_value <> '' AND wp.post_status =  'publish'
				ORDER BY genre, title";
		}
	
		$results = $wpdb->get_results($query);
		$html[] = '<div class="book-review-archives">';
		$size = $this->get_thumbnail_size();
	
		foreach($results as $result) {
			//Only include this post if it has been flagged to be shown in the archive.
			if ($result->archivePost == 1) {
				$thumb = wp_get_attachment_image($result->thumb, 'thumbnail');
			
				//No featured image. Use the cover URL with maximum dimensions set to be the thumbnail size from the Media Settings.
				if ($thumb == '') {
					//This is faster than adding to the main query. Consider using this approach for other fields instead of adding them to the main query.
					$values = get_post_custom_values('book_review_cover_url', $result->post_id);
					$cover_url = $values[0];
					$thumb = '<a href="' . $cover_url . '"><img src="' . $cover_url . '" style="max-width:' . $size[0] . 'px; max-height:' . $size[1] . 'px;" /></a>';
				}
				else {
					$url = wp_get_attachment_image_src($result->thumb, 'thumbnail');
					$thumb = '<a href="' . $url[0] . '">' . $thumb . '</a>';
				}
								
			
				if ($type == 'title') {
					//Get first letter of title.
					$current = strtoupper(substr($result->title, 0, 1));
				
					if (isset($previous) && ($current != $previous)) {
						//Check if both titles start with a number. In that case, don't end the list.
						if (is_numeric($current) && is_numeric($previous)) {
							//Do nothing.
						}
						else {
							$html[] = '</ul>';
						}
					}
	
					if ($current != $previous) {
						//Check if both titles start with a number. In that case, don't create a new heading.
						if (is_numeric($current)) {
							if (is_numeric($previous)) {
								//Do nothing.
							}
							else {
								$html[] = '<h4 class="header">#</h4>';
							
								if ($show_cover == 'true') {
									$html[] = '<ul class="thumbs">';
								}
								else {
									$html[] = '<ul>';
								}
							}
						}
						else {
							$html[] = '<h4 class="header">' . $current . '</h4>';
						
							if ($show_cover == 'true') {
								$html[] = '<ul class="thumbs">';
							}
							else {
								$html[] = '<ul>';
							}
						}
					}
				}
				else {
					$current = $result->genre;
				
					if (isset($previous) && ($current != $previous)) {
						$html[] = '</ul>';
					}
				
					if ($current != $previous) {
						$html[] = '<h4 class="header">' . $current . '</h4>';
					
						if ($show_cover == 'true') {
							$html[] = '<ul class="thumbs">';
						}
						else {
							$html[] = '<ul>';
						}
					}
				}
			
				$html[] = '<li>';
			
				//Cover
				if ($show_cover == 'true') {
					if ($thumb != '') {
						$html[] .= $thumb;
					}
				}
				
				//Title and author
				if ($show_cover == 'true') {
					$html[] = '<h5 class="title"><a href="'. get_permalink($result->post_id) . '">' . $result->title . '</a></h5>';
				
					if (!empty($result->author)) {
						$html[] = '<p>by ' . $result->author . '</p>';
					}
				}
				else {
					$html[] = '<p><a href="'. get_permalink($result->post_id) . '">' . $result->title . '</a>';
				
					if (!empty($result->author)) {
						$html[] = 'by ' . $result->author;
					}
				}
			
				//Rating
				if ($show_rating == 'true') {
					$values = get_post_custom_values('book_review_rating', $result->post_id);
					$ratings = get_option('book_review_ratings');
					$rating = $values[0];
				
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
					
						if ($show_cover == 'true') {
							$html[] = '<p><img src="' . $src . '" /></p>';
						}
						else {
							$html[] = '<img src="' . $src . '" />';
						}
					}
				}
		
				if ($show_cover == 'false') {
					$html[] = '</p>';
				}
			
				$html[] = '</li>';
			
				$previous = $current;
			}
		}
	
		$html[] = '</div>';
	
		return implode("\n", $html);
	}

	private function get_thumbnail_size() {
		$dimensions = array();
	
		foreach(get_intermediate_image_sizes() as $s) {
			if (in_array($s, array('thumbnail'))) {
				$dimensions[0] = get_option($s . '_size_w');
				$dimensions[1] = get_option($s . '_size_h');
				break;
			}
		}

		return $dimensions;
	}
}

$book_review = new BookReview();
?>