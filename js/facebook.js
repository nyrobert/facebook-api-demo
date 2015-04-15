/* jshint browser:true, jquery:true */
/* global config, FB */
$(function() {
	'use strict';

	function statusChangeCallback(response) {
		console.log('statusChangeCallback');
		console.log(response);
		if (response.status === 'connected') {
			testAPI();
		} else if (response.status === 'not_authorized') {
			document.getElementById('status').innerHTML = 'Please log into this app.';
		} else {
			document.getElementById('status').innerHTML = 'Please log into Facebook.';
		}
	}

	function checkLoginState() {
		FB.getLoginStatus(function(response) {
			statusChangeCallback(response);
		});
	}

	function testAPI() {
		console.log('Welcome!  Fetching your information.... ');
		FB.api('/me', function(response) {
			console.log('Successful login for: ' + response.name);
			document.getElementById('status').innerHTML =
				'Thanks for logging in, ' + response.name + '!';
		});
	}

	window.fbAsyncInit = function() {
		FB.init({
			appId:   config.appId,
			xfbml:   true,
			version: 'v2.3'
		});

		FB.getLoginStatus(function(response) {
			statusChangeCallback(response);
		});
	};

	(function(d, s, id) {
		var js;
		var fjs = d.getElementsByTagName(s)[0];
		if (d.getElementById(id)) {return;}
		js = d.createElement(s); js.id = id;
		js.src = '//connect.facebook.net/en_US/sdk.js';
		fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));
});
