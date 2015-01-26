<p>
  <?php _e( 'This plugin uses the Google Books API to automatically ' .
    'populate the details of a book. In order to take advantage of ' .
    'this feature, you must first sign up for and enter an API key as ' .
    'described ' .
    '<a href="http://donnapeplinskie.com/wordpress-book-review-plugin/"' .
    ' target="_blank">here</a>.', $this->plugin_name ) ?>
</p>

<form action="options.php" method="post">
  <?php
    @settings_fields( 'advanced_options' );
    @do_settings_fields( 'advanced_options' );
  ?>

  <table class="form-table">
    <tbody>
      <!-- Google API Key -->
      <tr>
        <th>
          <label for="book_review_api_key">
            <?php _e( 'Google API Key', $this->plugin_name ) ?>:
            <a href="#" class="tooltip">
              <?php echo $tooltip ?>
              <span>
                <?php _e( 'Your Google API key obtained from the Google ' .
                  'Developers Console.', $this->plugin_name ) ?>
              </span>
            </a>
          </label>
        </th>
        <td>
          <input id="book_review_api_key" class="text-input" type="text"
            name="book_review_advanced[book_review_api_key]"
            value="<?php echo esc_attr( $advanced['book_review_api_key'] ); ?>">
        </td>
      </tr>
    </tbody>
  </table>

  <?php @submit_button(); ?>
</form>