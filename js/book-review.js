var file_frame;

jQuery(document).ready(function($) {
    $(".color-picker").wpColorPicker();
    
    //Uploading files
    jQuery(".upload-image-button").live("click", function(event) {
        event.preventDefault();
    
        //If the media frame already exists, reopen it.
        if (file_frame) {
            file_frame.open();
            return;
        }
    
        //Create the media frame.
        file_frame = wp.media.frames.file_frame = wp.media({
            title: jQuery(this).data("uploader_title"),
            button: {
                text: jQuery(this).data("uploader_button_text"),
            },
            multiple: false  //Set to true to allow multiple files to be selected.
        });
    
        //When an image is selected, run a callback.
        file_frame.on("select", function() {
            //We set multiple to false so only get one image from the uploader
            attachment = file_frame.state().get("selection").first().toJSON();
            jQuery("#book_review_cover_url").val(attachment.url);
            jQuery("#book_review_cover_image").attr("src", attachment.url).show();
        });
    
        //Finally, open the modal.
        file_frame.open();
    });
    
    jQuery("#book_review_cover_url").live("change", function(event) {
        if (jQuery(this).val() != "") {
            jQuery("#book_review_cover_image").attr("src", jQuery(this).val()).show();
        }
        else {
            jQuery("#book_review_cover_image").attr("src", "").hide();
        }
    });
});

function showRatingImages() {
    if (jQuery("#book_review_rating_default").attr("checked")) {
        jQuery(".rating").hide();
    }
    else {
        jQuery(".rating").show();
    }
}

function showLinks() {
    var i,
        numLinks = parseInt(jQuery("#book_review_num_links").val());
    
    if (numLinks == 0) {
        jQuery(".links").hide();
    }
    else {
        jQuery(".links").show();
    }
        
    for (i = 1; i <= numLinks; i++) {
        jQuery("#link" + i).show();
    }
    
    for (i = numLinks + 1; i <= 5; i++) {
        jQuery("#link" + i).hide();
    }
}