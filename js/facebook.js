/* jshint browser: true, jquery: true, devel: true */
/* global define, FB, config */
define(['facebooksdk', 'login'], function(FB) {
	'use strict';

	function statusChangeCallback(response) {
		console.log('statusChangeCallback');
		console.log(response);

		if (response.status === 'connected') {
			// send response.authResponse.accessToken to the server
			// send response.authResponse.userID to the server
			// long lived token needed! re-verify needed on the server (with app_id and user_id)

			testAPI();
		} else if (response.status === 'not_authorized') {
			document.getElementById('status').innerHTML = 'Please log into this app.';
		} else {
			document.getElementById('status').innerHTML = 'Please log into Facebook.';
		}

		$('.sign-in-modal').modal('hide');
	}

	$('button.btn-facebook-login').on('click', function(event) {
		event.preventDefault();
		event.stopPropagation();

		FB.login(function(response) {
			statusChangeCallback(response);
		}, {scope: 'public_profile,email'});
	});

	function testAPI() {
		console.log('Welcome!  Fetching your information.... ');

		FB.api('/me', function(response) {
			console.log('Successful login for: ' + response.name);
			document.getElementById('status').innerHTML =
				'Thanks for logging in, ' + response.name + '!';
		});
	}

	FB.init({
		appId:   config.appId,
		version: 'v2.3'
	});

	FB.getLoginStatus(function(response) {
		statusChangeCallback(response);
	});
});
