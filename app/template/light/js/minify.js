function replace_first ( r, s ) {
	var ns = '';
	for ( var i = 0; i < s.length; i++ ) {
		if ( i == 0 ) {
			ns = r;
		}
		else {
			ns += s[i];
		}
	}

	return ns;
}

$(document).ready(function(){
	$('.list .video').live( 'click', function(){
		var $this = $(this).find('a');

		$('#modal .content').html( '' );

		if ( !$('#modal').length ) {
			$('body').append( '<div id="modal"><div class="overlay"></div><div class="content"></div></div>' );
		}

		var href = replace_first( ':', $this.attr('href') );

		$.post( URL+'ajax'+href, function( result ) {
			$('#modal').fadeIn('slow', function(){
				$('#modal .content').html( result );
			});
		});

		return false;
	});

	$('#modal .overlay, #modal .close').live( 'click', function(){
		$('#modal').fadeOut('slow');
	});

	$('.list .video').live( 'mousemove', function(){
		var $this = $(this).find('a');
		var $video = $(this).find('.overlay');

		var margin = 5;

		if ( !$('#viewTitle').length ) {
			$('body').append('<div id="viewTitle"></div>');
		}

		$('#viewTitle').html( $this.attr('data-title') );

		$('#viewTitle').show();
		$('#viewTitle').css({
			'position' : 'absolute',
			'float' : 'left'
		});

		var offset = $video.offset();

		var top = offset.top - $('#viewTitle').outerHeight() - margin;

		if ( top < 52 ) {
			top += $video.outerHeight() + $('#viewTitle').outerHeight() + ( margin * 2 );
		}

		var left = ( offset.left + ( $video.width() / 2 ) ) - ( $('#viewTitle').width() / 2 );

		$('#viewTitle').css({
			'top' : top,
			'left' : left
		});
	});

	$('body').live( 'mousemove', function(e){
		if ( e.clientX < 1 && $('#right').width() > 0 ) {
			$('#left').animate({
			    'left' : '0'
			});

			$('#content').animate({
			    'left' : $('#right').width()
			});

			$('#right').animate({
			    'width' : '0',
			    'display' : 'none'
			});
		}
	});

	$('.list .video').live( 'mouseleave', function(){
		$('#viewTitle').hide();
	});

	$('ul.tab li:not(".right") a').live( 'click', function(){
		var $this = $(this);
		var tab = $this.attr('data-tab');

		$('ul.tab li').removeClass('current');
		$this.parent().addClass('current');

		$('div.tab').removeClass('current');
		$('div.tab.'+tab).addClass('current');

		return false;
	});

	$('ul.tab li .icon.watch a').live( 'click', function(){
		var $this = $(this);

		var href = replace_first( ':', $this.attr('href') );

		if ( $('.tab.watch').length == 0 ) {
			$.post( URL+'ajax'+href, function( result ) {
				$('.tab.buy').after( result );
			});
		}

		var $this = $(this);
		var tab = 'watch';

		$('ul.tab li').removeClass('current');
		$this.parent().parent().addClass('current');

		$('div.tab').removeClass('current');
		$('div.tab.'+tab).addClass('current');

		return false;
	});

	var height_top  = $('ul#menu li.top > a').eq(0).outerHeight(true);
	var count_top  = $('ul#menu li.top').length;

	$('ul#menu li.top').live( 'mouseenter', function(){
		var $this = $(this);
		var $menu = $('ul#menu');

		$this.addClass('hover');

		$('ul#menu li.top:not(".hover")').animate({
			'height' : height_top
		});

		$this.animate({
			'height' : $menu.height() - ( height_top * ( count_top - 1)  )
		});
	});

	$('ul#menu li.top').live( 'mouseleave', function(){
		var $this = $(this);

		$this.removeClass('hover');

		var $sub = $(this).find('ul.sub');
		$sub.css('top', 0);
	});

	$('ul#menu').live( 'mouseleave', function(){
		$('ul#menu li.top').animate({
			'height' : '171px'
		});

		var $sub = $(this).find('ul.sub');
		$sub.css('top', 0);
	});

	$('ul#menu li.top').live( 'mousemove', function(e){
		var $top = $(this);
		var $sub = $(this).find('ul.sub');

		var top_offset = 0;

		var offset = $top.offset();
		offset = offset.top + height_top + $('ul#menu li.top ul.sub li').eq(0).outerHeight(true);

		var height = $top.height() - height_top;
		var height_sub = $sub.height();

		var max_top = ( height_sub - height ) * -1;

		var posY = e.pageY;

		var middle = ( ( offset + height ) - posY );

		var pourcent = ( 100 / ( height / middle ) );

		if ( pourcent > 100 ) {
			pourcent = 100;
		}

		if ( pourcent < 0 ) {
			pourcent = 0;
		}

		var new_height = height_sub * ( pourcent / 100 );
		var decalage = height_sub - new_height;
		var new_top = top_offset - decalage;

		if ( new_top < max_top ) {
			new_top = max_top;
		}

		$sub.animate({
			top : new_top
		}, 10);
	});
});