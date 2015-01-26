<form action="options.php" method="post">
  <?php
    @settings_fields( 'general_options' );
    @do_settings_fields( 'general_options' );
  ?>

  <table class="form-table">
    <tbody>
      <!-- Review Box Position -->
      <tr>
        <th>
          <?php _e( 'Review Box Position', $this->plugin_name ) ?>:
          &nbsp;&nbsp;
          <a href="#" class="tooltip">
            <?php echo $tooltip ?>
            <span>
              <?php _e( 'Whether to show the review box at the top or ' .
                'bottom of a post.', $this->plugin_name ) ?>
            </span>
          </a>
        </th>
        <td>
          <!-- Top -->
          <input id="book_review_box_position_top" type="radio"
            name="book_review_general[book_review_box_position]" value="top"
            <?php echo checked( 'top', $general['book_review_box_position'],
              false ); ?>>
          <label for="book_review_box_position_top">
            <?php _e( 'Top', $this->plugin_name ) ?>
          </label>

          <!-- Bottom -->
          <input id="book_review_box_position_bottom" type="radio"
            name="book_review_general[book_review_box_position]"
            value="bottom"
            <?php echo checked( 'bottom',
              $general['book_review_box_position'], false ); ?>>
          <label for="book_review_box_position_bottom">
            <?php _e( 'Bottom', $this->plugin_name ) ?>
          </label>
        </td>
      </tr>

      <!-- Review Box Background Color -->
      <tr>
        <th>
          <label for="book_review_bg_color">
            <?php _e( 'Review Box Background Color', $this->plugin_name ) ?>:
          </label>
        </th>
        <td>
          <input id="book_review_bg_color" class="color-picker" type="text"
            name="book_review_general[book_review_bg_color]"
            value="<?php echo esc_attr( $general['book_review_bg_color'] ); ?>">
        </td>
      </tr>

      <!-- Review Box Border Color -->
      <tr>
        <th>
          <label for="book_review_border_color">
            <?php _e( 'Review Box Border Color', $this->plugin_name ) ?>:
          </label>
        </th>
        <td>
          <input id="book_review_border_color" class="color-picker"
            type="text" name="book_review_general[book_review_border_color]"
            value="<?php echo esc_attr( $general['book_review_border_color'] ); ?>">
        </td>
      </tr>

      <!-- Release Date Format -->
      <tr>
        <th>
          <label for="book_review_date_format">
            <?php _e( 'Release Date Format', $this->plugin_name ) ?>:
            <a href="#" class="tooltip">
              <?php echo $tooltip ?>
              <span>
                <?php _e( 'Format that the Release Date will be shown in.',
                  $this->plugin_name ) ?>
              </span>
            </a>
          </label>
        </th>
        <td>
          <select id="book_review_date_format"
            name="book_review_general[book_review_date_format]">
            <?php $this -> render_date_format_field(); ?>
          </select>
        </td>
      </tr>
    </tbody>
  </table>

  <?php @submit_button(); ?>
</form>