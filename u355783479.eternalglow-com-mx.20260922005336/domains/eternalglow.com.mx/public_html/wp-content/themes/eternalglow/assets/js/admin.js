var $ = jQuery.noConflict();

function custom_theme_image(){
	
	$('body').on('click', '.add-custom-theme-image', function(){
		var _img = $(this).data('img');
		var _input = $(this).data('input');
		const media = wp.media({
            library: {
                type: 'image'
            },
            multiple: false,
            title: '',
        });
        media.on('open', function() {
        	var val = $(_input).val();
        	if(val && val > 0){
				var selection = media.state().get('selection');
	            attachment = wp.media.attachment(val);
				attachment.fetch();
				selection.add( attachment ? [ attachment ] : [] );
			}
	    });
		media.on('select', function() {
            const selection = media.state().get('selection');
            selection.each(function (attachment){
                if(attachment.attributes.type == 'image'){
                	var url = attachment.attributes.url;
					var id = attachment.id;
					$(_img).attr('src', url);
					$(_img).addClass('active');
					$(_input).val(id);
                }
            });
        });
        media.open();
	});
	
	$('body').on('click', '.remove-custom-theme-image', function(e){
		var _img = $(this).data('img');
		var _input = $(this).data('input');
		$(_img).attr('src', '');
		$(_img).removeClass('active');
		$(_input).val('');
		e.preventDefault();
	});
	
	$('.add-custom-theme-file').on('click', function(){

		var _img = $(this).data('img');
		var _input = $(this).data('input');
		var _span = $(this).data('span');
		const media = wp.media({
            /*library: {
                type: 'application'
            },*/
            multiple: false,
            title: '',
        });
        media.on('open', function() {
        	var val = $(_input).val();
        	if(val && val > 0){
				var selection = media.state().get('selection');
	            attachment = wp.media.attachment(val);
				attachment.fetch();
				selection.add( attachment ? [ attachment ] : [] );
			}
	    });
		media.on('select', function() {
            const selection = media.state().get('selection');
            selection.each(function (attachment){
            	var icon = attachment.attributes.icon;
            	var filename = attachment.attributes.filename;
				var id = attachment.id;
				$(_img).attr('src', icon);
				$(_img).addClass('active');
				$(_span).html(filename);
				$(_input).val(id);
            });
        });
        media.open();
	});
	
	$('.remove-custom-theme-file').on('click', function(e){
		var _img = $(this).data('img');
		var _input = $(this).data('input');
		var _span = $(this).data('span');
		$(_img).attr('src', '');
		$(_img).removeClass('active');
		$(_span).html('');
		$(_input).val('');
		e.preventDefault();
	});

}

$(function() {

	custom_theme_image();

	
	$('#add-image-gallery').on('click', function() {

		const media = wp.media({
            library: {
                type: 'image'
            },
            multiple: true,
            title: '',
        });
		media.on('select', function() {
            const selection = media.state().get('selection');
            selection.each(function (attachment){
				var li = $('<li>', {});
				var a = $('<a>', {
					'href': '#',
					'class': 'remove',
					'html': '<span class="dashicons dashicons-dismiss"></span>'
				});
				a.click(function(e){
					li.fadeOut('fast', function(){
						li.remove();
					});
					e.preventDefault();
				});
				var img = $('<img>', {
					'src': attachment.attributes.sizes.thumbnail.url,
					'alt': '',
					'title': attachment.attributes.title
				});
				var input = $('<input>', {
					'type': 'hidden',
					'value': attachment.id,
					'name': 'gallery[]'
				});
				li.append(a);
				li.append(img);
				li.append(input);
				$('#gallery-sortable').append(li);

            });
        });
		media.open();
		
	});

	$('.gallery-sortable li').each(function(){
		var li = $(this);
		var a = $('a.remove', li);
		a.on('click', function(e){
			li.fadeOut('fast', function(){
				li.remove();
			});
			e.preventDefault();
		});
	});

	if($('.gallery-sortable').length > 0){
		$('.gallery-sortable').sortable({
			revert: true
	    });
	}

	if($('.datepicker').length > 0){
		$('.datepicker').datepicker({
			changeMonth: true,
			changeYear: true,
			dateFormat: "dd/mm/yy"
		});
	}

	$('.colorpicker').wpColorPicker();

	$('.wpb_vc_param_value.wpb-colorpicker').each(function () {
        const $input = $(this);
		console.log(123123);
        // Si aún no tiene colorPicker inicializado, lo forzamos con "clear"
        if (!$input.hasClass('wp-color-picker')) {
            $input.wpColorPicker({
                clear: true // Esto muestra el botón de limpiar
            });
        }
    });
	
});

$(window).load(function(){

});

$(document).ajaxComplete(function(event, xhr, options) {
    if (xhr && xhr.readyState === 4 && xhr.status === 200 && options.data) {
		if(typeof options.data === 'string'){
			if(options.data.indexOf('action=add-tag') >= 0){
				/*var res = wpAjax.parseAjaxResponse(xhr.responseXML, 'ajax-response');
				if (!res || res.errors) {
					return;
				}*/
				$('.custom-theme-image').val('');
				$('.custom-theme-img').removeClass('active');
				$('.custom-theme-file').val('');
				$('.custom-theme-filename').html('');
				$('.wp-color-picker').val('');
				$('.wp-color-result').removeAttr('style');
			}
			if(options.data.indexOf('widget-jss_image_widget') >= 0 && options.data.indexOf('add_new=multi') >= 0){
				custom_theme_image();
			}
			if(options.data.indexOf('widget-jss_popup_widget') >= 0 && options.data.indexOf('add_new=multi') >= 0){
				custom_theme_image();
			}
		}
    }
});