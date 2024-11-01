var buttonDefaultSelected = false;
jQuery(document).on("DOMNodeInserted", function(){
	jQuery('a.media-button-insert')
		.attr('disabled',false)
		.removeClass('media-button-insert')
		.on('click',function(){
			jQuery('a.media-modal-close').click();
			window.location.reload();
			e.stopPropagation();
			e.preventDefault();
			return false;
		});
	jQuery('.media-frame-router .media-router .media-menu-item').each(function(index, element) {
		if(jQuery(element).text()==_wpMediaViewsL10n.uploadFilesTitle && !buttonDefaultSelected) {
			buttonDefaultSelected = true;
			jQuery(element).click();
		}
    });
	jQuery('select.attachment-filters').val('uploaded').trigger('change').hide();
	jQuery('.media-frame-menu').remove();
});
jQuery(document).ready( function($) {
	_wpMediaViewsL10n.insertIntoPost = 'Done';
    var uploader;
    $('#insert-media-button').click(function(e) {
        e.preventDefault();
        if (uploader) {
            uploader.open();
            return;
        }
        uploader = wp.media.frames.file_frame = wp.media({
            title: 'Done',
            button: {
                text: 'Done'
            },
			frame: 'post',
            multiple: false,
			state: 'insert'
        });
        uploader.on('select', function() {
            attachment = uploader.state().get('selection').first().toJSON();
        });
        uploader.open();
    });
});
function toggleOptions() {
	jQuery('#engine_options').toggle(500);
}