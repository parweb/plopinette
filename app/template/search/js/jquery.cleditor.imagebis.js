var URL_SITE = '/twirling/';

/**
 @preserve CLEditor Imagebis Plugin v1.0.2
 http://premiumsoftware.net/cleditor
 requires CLEditor v1.2.2 or later
 
 Copyright 2010, Chris Landowski, Premium Software, LLC
 Dual licensed under the MIT or GPL Version 2 licenses.
*/

// ==ClosureCompiler==
// @compilation_level SIMPLE_OPTIMIZATIONS
// @output_file_name jquery.cleditor.imagebis.min.js
// ==/ClosureCompiler==

(function($) {
	// Define the imagebis button
	$.cleditor.buttons.imagebis = {
		name: "imagebis",
		image: "image.gif",
		title: "Inserer une image",
		command: "insertimage",
		popupName: "imagebis",
		popupClass: "cleditorPrompt",
		popupContent:         
		'<form enctype="multipart/form-data"><label><input type="radio" name="align" value="top" />Haut</label><label><input type="radio" name="align" value="right" />Droite</label><label><input type="radio" name="align" value="bottom" />Bas</label><label><input type="radio" name="align" value="left" />Gauche</label><br /><label>Image: <input type="file" name="image" /></label></form>',
		buttonClick: imagebisButtonClick
	};

	// Add the button to the default controls
	$.cleditor.defaultOptions.controls = $.cleditor.defaultOptions.controls
		.replace("image ", "imagebis ");

	// Imagebis button click event handler
	function imagebisButtonClick(e, data) {
		// Wire up the submit button click event handler
		$(data.popup).find("input[type=file]")
			.unbind("change")
			.bind("change", function(e) {
				var $_this = $(this);

				// Get the editor
				var editor = data.editor; 
				$_this.upload(URL_SITE+'ajax:photo/upload/align:'+ $(data.popup).find("input:radio[name=align]:checked").val() +'/', function(res) {
					var html = res.image;

					// Insert the html
					if (res.image) {
						editor.execCommand(data.command, html, null, data.button);
						editor.find( 'img[src="'+html+'"]' ).attr( 'align', $(data.popup).find("input:radio[name=align]:checked").val() );
					}
				}, 'json');

				// reset popup
				$(this).val('');
				editor.hidePopups();
				editor.focus();
			}
		);
	}
})(jQuery);