$(document).ready(function(){	
	$('je ne sais pas encore').live('click', function(){
		var $this = $(this);

		FB.ui({
			method: 'feed',
			link: $this.attr('href'),
			picture: 'http://www.dailymatons.com/app/template/default/images/logo.png',
			name: 'Film en Streaming Haute définition',
			caption: 'dailymatons.com',
			description: 'Faite votre choix entre plus de 2500 films classés par genres, acteurs, réalisateurs ou nationnalités.'
		}, function(response){});

		return false;
	});
});