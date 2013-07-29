window.fbAsyncInit = function() {
	FB.init({
		appId : '200606866671822',
		status : true, 
		cookie : true,
		xfbml : true,
		oauth : true,
	});
};

(function(d){
	var js, id = 'facebook-jssdk'; if (d.getElementById(id)) {return;}
	js = d.createElement('script'); js.id = id; js.async = true;
	js.src = "//connect.facebook.net/fr_FR/all.js";
	d.getElementsByTagName('head')[0].appendChild(js);
}(document));

jQuery( function ( $ ) {
	$('.facebook-connect').live( 'click', function(){
		var login = $('.facebook-connect.login').attr('href');
		var register = $('.facebook-connect.register').attr('href');

		FB.login( function (res) {
			if ( res.authResponse ) {
				window.location = login;
			}
			else {
				window.location = register;
			}
		}, {
			scope : 'email'
		});

		return false;
	});
});