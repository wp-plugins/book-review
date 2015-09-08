<?php

/**
 * Defines functionality for serializing the options saved in the meta box.
 *
 * @link       http://wpreviewplugins.com/
 * @since      2.1.8
 *
 * @package    Book_Review
 * @subpackage Book_Review/admin
 */

/**
 * Defines functionality for serializing the options saved in the meta box.
 *
 * @package    Book_Review
 * @subpackage Book_Review/admin
 * @author     Donna Peplinskie <support@wpreviewplugins.com>
 */
class Book_Review_Meta_Box {
  /**
   * The ID of this plugin.
   *
   * @since    2.1.8
   * @access   private
   * @var      string    $plugin_name    The ID of this plugin.
   */
  private $plugin_name;

  /**
   * Initialize the class and set its properties.
   *
   * @since    2.1.8
   * @var      string    $plugin_name       The name of the plugin.
   */
  public function __construct( $plugin_name ) {
    global $wpdb;

    $this->plugin_name = $plugin_name;
    $wpdb->book_review_custom_link_urls = "{$wpdb->prefix}book_review_custom_link_urls";
  }

  /**
   * Meta box setup function.
   *
   * @since    2.0.0
   */
  public function meta_box_setup() {
    add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
    add_action( 'save_post', array( $this, 'save_meta_box' ) );
  }

  /**
   * Add the meta box container.
   *
   * @since    1.0.0
   */
  public function add_meta_box( $post_type ) {
    $defaults = array();

    // Maintain backwards compatibility by showing the meta box for posts and all custom post types,
    // but only if the Post Types setting has never been saved.
    $defaults['book_review_post_types'] = array(
      'post' => '1'
    );

    $args = array(
      'public'   => true,
      '_builtin' => false
    );

    $cpts = get_post_types( $args, 'names' );

    // Add a default value for each custom post type.
    foreach ( $cpts as $cpt ) {
      $defaults['book_review_post_types'][$cpt] = '1';
    }

    $general = get_option( 'book_review_general' );
    $general = wp_parse_args( $general, $defaults );

    if ( isset( $general['book_review_post_types'] ) ) {
      $general['book_review_post_types'] = wp_parse_args( $general['book_review_post_types'], $defaults['book_review_post_types'] );
    }
    else {
      $general['book_review_post_types'] = $defaults['book_review_post_types'];
    }

    foreach ( $general['book_review_post_types'] as $key => $value ) {
      if ( ( $post_type == $key ) && ( $value == '1' ) ) {
        add_meta_box(
          'book-review-meta-box',
          esc_html__( 'Book Info', $this->plugin_name ),
          array( $this, 'render_meta_box' ),
          $post_type,
          'normal',
          'high'
        );

        do_action( 'book_review_meta_box', $post_type );

        break;
      }
    }
  }

  /**
   * Display meta box.
   *
   * @since    1.0.0
   *
   * @param    object    $post    Object for the current post.
   */
  public function render_meta_box( $post ) {
    // Get saved values from the database.
    $values = get_post_custom( $post->ID ) ;
    $ratings = get_option( 'book_review_ratings' );
    $advanced = get_option( 'book_review_advanced' );

    // Set the value for each key in $values.
    foreach ( array( 'book_review_isbn', 'book_review_title',
      'book_review_series', 'book_review_author', 'book_review_genre',
      'book_review_publisher', 'book_review_release_date', 'book_review_format',
      'book_review_pages', 'book_review_source', 'book_review_cover_url',
      'book_review_summary', 'book_review_rating', ) as $var ) {
      $$var = isset( $values[$var][0] ) ? $values[$var][0] : null;
    }

    $book_review_archive_post = isset( $values['book_review_archive_post'][0] ) ? $values['book_review_archive_post'][0] : '1';
    $api_key = isset( $advanced['book_review_api_key'] ) ? $advanced['book_review_api_key'] : '';
    $args = array(
      'textarea_rows' => 15,
      'media_buttons' => false
    );

    // Show IBSN and Get Book Info button if Google API Key has been entered.
    if ( $api_key == '' ) {
      $show_isbn = 'display: none;';
    }
    else {
      $show_isbn = 'display: block;';
    }

    // Show an image preview if applicable.
    if ( $book_review_cover_url == '' ) {
      $show_cover = 'display: none;';
    }
    else {
      $show_cover = 'display: block;';
    }

    // Default rating image.
    if ( $ratings['book_review_rating_default'] == '1' ) {
      if ( $book_review_rating == '1' ) {
        $src = plugin_dir_url( dirname(__FILE__) ) . 'includes/images/one-star.png';
      }
      else if ( $book_review_rating == '2' ) {
        $src = plugin_dir_url( dirname(__FILE__) ) . 'includes/images/two-star.png';
      }
      else if ( $book_review_rating == '3' ) {
        $src = plugin_dir_url( dirname(__FILE__) ) . 'includes/images/three-star.png';
      }
      else if ( $book_review_rating == '4' ) {
        $src = plugin_dir_url( dirname(__FILE__) ) . 'includes/images/four-star.png';
      }
      else if ( $book_review_rating == '5' ) {
        $src = plugin_dir_url( dirname(__FILE__) ) . 'includes/images/five-star.png';
      }
      else {
        $src = '';
      }
    }
    // Custom rating image.
    else {
      if ( $book_review_rating == '1' ) {
        $src = $ratings['book_review_rating_image1'];
      }
      else if ( $book_review_rating == '2' ) {
        $src = $ratings['book_review_rating_image2'];
      }
      else if ( $book_review_rating == '3' ) {
        $src = $ratings['book_review_rating_image2'];
      }
      else if ( $book_review_rating == '4' ) {
        $src = $ratings['book_review_rating_image2'];
      }
      else if ( $book_review_rating == '5' ) {
        $src = $ratings['book_review_rating_image2'];
      }
      else {
        $src = '';
      }
    }

    // Show the rating image.
    if ( empty( $src ) ) {
      $show_rating_image = 'display: none;';
    }
    else {
      $show_rating_image = 'display: block;';
    }

    wp_nonce_field( 'save_meta_box_nonce', 'book-review-meta-box-nonce' );

    include_once( 'partials/book-review-admin-meta-box.php' );
  }

  /**
   * Save meta box information.
   *
   * @since    1.0.0
   *
   * @param    object    $post_id    Object for the current post.
   */
  public function save_meta_box( $post_id ) {
    global $wpdb;

    if ( $this->user_can_save( $post_id, 'book-review-meta-box-nonce', 'save_meta_box_nonce' ) ) {
      $this->save_text_field( $post_id, 'book_review_isbn', $_POST['book_review_isbn']);
      $this->save_text_field( $post_id, 'book_review_title', $_POST['book_review_title']);
      $this->save_text_field( $post_id, 'book_review_series', $_POST['book_review_series']);
      $this->save_text_field( $post_id, 'book_review_author', $_POST['book_review_author']);
      $this->save_text_field( $post_id, 'book_review_genre', $_POST['book_review_genre']);
      $this->save_text_field( $post_id, 'book_review_publisher', $_POST['book_review_publisher']);
      $this->save_text_field( $post_id, 'book_review_release_date', $_POST['book_review_release_date']);
      $this->save_text_field( $post_id, 'book_review_format', $_POST['book_review_format']);
      $this->save_text_field( $post_id, 'book_review_pages', $_POST['book_review_pages']);
      $this->save_text_field( $post_id, 'book_review_source', $_POST['book_review_source']);
      $this->save_url_field( $post_id, 'book_review_cover_url', $_POST['book_review_cover_url']);
      $this->save_field( $post_id, 'book_review_summary', $_POST['book_review_summary']);
      $this->save_field( $post_id, 'book_review_rating', $_POST['book_review_rating']);
      $this->save_field( $post_id, 'book_review_archive_post', $_POST['book_review_archive_post']);

      // Save title used in archives.
      if ( isset( $_POST['book_review_title'] ) && ( strlen( trim( $_POST['book_review_title'] ) ) > 0 ) ) {
        update_post_meta( $post_id, 'book_review_archive_title', $this->get_archive_title() );
      }
      else {
        delete_post_meta( $post_id, 'book_review_archive_title' );
      }

      // For every entry in the custom_links table, save an entry to the custom_link_urls table.
      $sql = "SELECT custom_link_id FROM {$wpdb->book_review_custom_links} WHERE active = 1";
      $results = $wpdb->get_results( $sql );

      foreach( $results as $result ) {
        $link = $_POST['book_review_custom_link' . $result->custom_link_id];

        if ( isset( $link ) && strlen( trim( $link ) ) > 0 ) {
          $link = esc_url_raw( $link);
          $sql = "INSERT INTO {$wpdb->book_review_custom_link_urls} (post_id, custom_link_id, url)
            VALUES (%d, %d, %s) ON DUPLICATE KEY UPDATE url = %s";
          $sql = $wpdb->prepare($sql, $post_id, $result->custom_link_id, $link, $link);

          $wpdb->query($sql);
        }
        // Delete link from table if the field is empty.
        else {
          $wpdb->delete(
            $wpdb->book_review_custom_link_urls,
            array(
              'post_id' => $post_id,
              'custom_link_id' => $result->custom_link_id,
            ),
            array( '%d', '%d' )
          );
        }
      }
    }
  }

  private function save_text_field( $post_id, $name, $value ) {
    // Don't save empty rows.
    if ( isset( $value ) && ( strlen( trim( $value ) ) > 0 ) ) {
      update_post_meta( $post_id, $name, sanitize_text_field( $value ) );
    }
    else {
      delete_post_meta( $post_id, $name );
    }
  }

  private function save_url_field( $post_id, $name, $value ) {
    // Don't save empty rows.
    if ( isset( $value ) && ( strlen( trim( $value ) ) > 0 ) ) {
      update_post_meta( $post_id, $name, esc_url_raw( $value ) );
    }
    else {
      delete_post_meta( $post_id, $name );
    }
  }

  private function save_field( $post_id, $name, $value ) {
    // Don't save empty rows.
    if ( isset( $value ) && ( strlen( trim( $value ) ) > 0 ) ) {
      update_post_meta( $post_id, $name, $value );
    }
    else {
      delete_post_meta( $post_id, $name );
    }
  }

  /**
   * Verifies that the user who is currently logged in has permission to save the data
   * from the meta box to the database.
   *
   * @since    2.1.8
   * @param    integer    $post_id    The current post being saved.
   * @param    string     $nonce      The nonce used once to identify the serialization value.
   * @param    string     $action     The source of the action of the nonce being used.
   * @return   boolean                true if the user can save the information.
   */
  private function user_can_save( $post_id, $nonce, $action ) {
    $is_autosave = wp_is_post_autosave( $post_id );
    $is_revision = wp_is_post_revision( $post_id );
    $is_valid_nonce = ( isset( $_POST[ $nonce ] ) && wp_verify_nonce( $_POST[ $nonce ], $action ) );

    return ! ( $is_autosave || $is_revision ) && $is_valid_nonce;
  }

  /**
   * Display link fields in meta box.
   *
   * @since    2.0.0
   */
  public function render_links( $post ) {
    global $wpdb;

    // Get the link text and link URLs.
    $sql = "SELECT links.custom_link_id, links.text, urls.url
      FROM {$wpdb->book_review_custom_links} AS links
      LEFT OUTER JOIN {$wpdb->book_review_custom_link_urls} AS urls ON links.custom_link_id = urls.custom_link_id
        AND urls.post_id = $post->ID
        WHERE links.active = 1";

    $results = $wpdb->get_results( $sql );

    // Render links outside of PHP code, otherwise they will be slightly misaligned.
    foreach( $results as $result ) { ?>
      <div class="row">
        <label for="<?php echo esc_attr( 'book_review_custom_link' . $result->custom_link_id ); ?>">
          <?php echo esc_html( $result->text ); ?>:
        </label>
        <input type="text" id="<?php echo esc_attr( 'book_review_custom_link' . $result->custom_link_id ); ?>"
          name="<?php echo esc_attr( 'book_review_custom_link' . $result->custom_link_id ); ?>"
          value="<?php echo esc_url( $result->url ); ?>">
      </div>
    <?php
    }
  }

  /**
   * Display rating dropdown in meta box.
   *
   * @since    2.0.0
   */
  public function render_rating( $rating ) {
    // Show the Rating dropdown.
    $items = array(
      '-1' => __( 'Select...', $this->plugin_name ),
      '1' => '1',
      '2' => '2',
      '3' => '3',
      '4' => '4',
      '5' => '5'
    );

    foreach ( $items as $type => $item ) {
      $selected = ( $rating == $type ) ? 'selected="selected"' : '';

      echo '<option value="' . esc_attr( $type ) . '" ' . $selected . '>' . esc_html( $item ) . '</option>';
    }
  }

  /**
   * Get book details from the Google Books API.
   *
   * @since    2.0.0
   */
  public function get_book_info() {
    if ( wp_verify_nonce( $_REQUEST['nonce'], 'ajax_isbn_nonce' ) ) {
      $advanced = get_option( 'book_review_advanced' );
      $api_key = isset( $advanced['book_review_api_key'] ) ? $advanced['book_review_api_key'] : '';
      $country = isset( $advanced['book_review_country'] ) ? $advanced['book_review_country'] : '';

      // Don't make a request to the API if there is no API key or ISBN.
      if ( !empty( $api_key ) && !empty( $_POST['isbn'] ) && ( strlen( trim( $_POST['isbn'] ) ) > 0 ) ) {
        $url = $this->get_api_url( $_POST['isbn'], $api_key, $country );
        $response = wp_remote_get( esc_url_raw( $url ) );

        try {
          if ( is_wp_error( $response ) ) {
            $result['status'] = 'error';
            $result['data'] = $response->get_error_message();
          }
          else if ( $response['response']['code'] == 200 ) {
            $json = json_decode( $response['body'] );

            // Format the Release Date.
            if ( isset( $json->items[0]->volumeInfo->publishedDate ) ) {
              $published_date = $json->items[0]->volumeInfo->publishedDate;
              $obj_date = DateTime::createFromFormat( 'Y-m-d', $published_date );

              // Check that date is in the expected Y-m-d format.
              if ( $obj_date != false ) {
                // Format the date as per the WordPress "Date Format" setting.
                $published_date = $obj_date->format( get_option( 'date_format' ) );
              }

              // Add the date to the result.
              $result['releaseDate'] = $published_date;
            }

            $body = $response['body'];
            $result['status'] = 'success';
            $result['data'] = $body;
          }
          else {
            $result['status'] = 'error';
            $result['data'] = $response['response']['code'] . ' ' . $response['response']['message']
              . ' to ' . esc_url( $url );
          }
        }
        catch ( Exception $ex ) {
          $result['status'] = 'error';
          $result['data'] = 'Exception: ' . $ex;
        }
      }
      else {
        $result['status'] = 'error';
        $result['data'] = 'No API key or empty ISBN';
      }
    }
    else {
      $result['status'] = 'error';
      $result['data'] = 'Invalid nonce';
    }

    $result = json_encode( $result );

    echo $result;
    wp_die();
  }

  /**
   * Get the Google Books API request URL.
   *
   * @since    2.1.14
   */
  private function get_api_url( $isbn, $api_key, $country ) {
    $url = 'https://www.googleapis.com/books/v1/volumes?q=isbn:' . sanitize_text_field( $isbn ) .
      '&key=' . sanitize_text_field( $api_key );

    if ( !empty( $country ) ) {
      $url = add_query_arg( 'country', $country, $url );
    }

    return $url;
  }

  /**
   * Move common stopwords to end of Title.
   *
   * @since    1.0.0
   */
  private function get_archive_title() {
    $title = trim( $_POST['book_review_title'] );
    $stopwords = array( esc_html__( 'the', $this->plugin_name ), esc_html__( 'a', $this->plugin_name ),
      esc_html__( 'an', $this->plugin_name ) );

    // Translations may specify multiple stopwords for each English word. Separate them into a
    // comma-delimited list in order to avoid a multi-dimensional array.
    $stopwords = implode( ',', $stopwords );

    // Now put them back into a one-dimensional array.
    $stopwords = explode( ',', $stopwords );

    foreach ( $stopwords as $stopword ) {
      $stopword = trim( $stopword );

      // Check if first characters of the title is a stop word. Add a space at the end of the
      // stopword so that only full words are matched.
      $substring = substr( $title, 0, strlen( $stopword ) + 1 );

      // Move stopword to the end if a match is found.
      if ( strtolower( $substring ) == ( $stopword . ' ' ) ) {
        return sanitize_text_field( substr( $title, strlen( $stopword ) + 1 ) . ', ' . $substring );
      }
    }

    return sanitize_text_field( $title );
  }
}