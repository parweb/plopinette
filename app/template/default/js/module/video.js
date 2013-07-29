$(document).ready(function(){
	$('#video-infos #actions .link').live('mouseenter', function(){
		var $this = $(this);

		$this.find('li a').animate({ 'width' : '200px' }, 700);
	});

	$('#video-infos #actions .link').live('mouseleave', function(){
		var $this = $(this);
		var $ul = $('li.collection ul');
		
		$ul.slideUp('fast');

		$this.find('li a').animate({ 'width' : '0px' }, 700);
	});
	
	$('#video-infos #actions .link ul li.alert a').live( 'click', function(){
		var $this = $(this);
		var link = $this.attr('href');

		$.post( link, function(res){
			$this.text(res);
		});
		return false;
	});
	
	$('#video-infos #actions .link li.fb a').live('click', function() {
		var $this = $(this);

		FB.ui({
			method: 'feed',
			link: location.href,
			picture: 'http://www.dailymatons.com'+$('.poster').attr('src'),
			name: $('#preview #title h1').text(),
			caption: $('.tab-content .allocine > span.genre span').text(),
			description: $('.tab-content .allocine > span.abstract span').text()
		}, function(response){});

		return false;
	});
	
	$('#video-infos #actions .link ul li.collection a').live('click', function(){
		var $this = $(this);
		var $ul = $this.next('ul');

		$ul.slideToggle('slow');

		return false;
	});
	
	$('.collection ul form').live('submit', function() {
		var $this = $(this);

		if ( $this.find('input').eq(0).val() != '' ) {
			var $li = $this.parents('li');

			var url = $this.attr('action');

			$.post( url, $this.serialize(), function( result ) {
				$li.eq(0).after('<li><a href="#">'+$this.find('input').eq(0).val()+'</a></li>');
				$this.find('input').eq(0).val('');
			});
		}
		else {
			alert('Veuillez entrer une valeur');
		}

		return false;
	});
	
	$('.collection ul li a').live('click', function() {
		var $this = $(this);
		var $li = $this.parent();
		
		var collection_id = $this.data('id'); 
		var video_id = $('#preview').data('id');
		
		$.post( URL+'collectioner/add/', { 'collectioner':{'collection_id' : collection_id, 'video_id' : video_id} }, function( result ) {
			$li.toggleClass('selected');
		});

		return false;
	});
	
});