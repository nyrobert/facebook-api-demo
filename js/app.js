/* jshint browser:true, jquery:true */
/* global config, FB */
$(function() {
	'use strict';

	window.fbAsyncInit = function() {
		FB.init({
			appId:   config.appId,
			xfbml:   true,
			version: 'v2.3'
		});
	};

	var signInOverlay = $('.sign-in-modal');
	signInOverlay.find('button.btn-primary').on('click', function(event) {
		event.preventDefault();
		event.stopPropagation();

		var email    = $('#sign-in-email');
		var password = $('#sign-in-password');

		if (!email.val() || !password.val()) {
			return;
		}

		$.ajax({
			url:      signInOverlay.find('form.sign-in').attr('action'),
			type:     'POST',
			dataType: 'json',
			data: {
				email:    email.val(),
				password: password.val()
			}
		}).done(function(data) {
			if (data.success) {
				location.reload();
			} else {
				alert(data.errorMessage);
			}
		});
	});

	var registerOverlay = $('.register-modal');
	registerOverlay.find('button').on('click', function(event) {
		event.preventDefault();
		event.stopPropagation();

		var email    = $('#register-email');
		var password = $('#register-password');

		if (!email.val() || !password.val()) {
			return;
		}

		$.ajax({
			url:      registerOverlay.find('form').attr('action'),
			type:     'POST',
			dataType: 'json',
			data: {
				email:    email.val(),
				password: password.val()
			}
		}).done(function(data) {
			if (data.success) {
				email.val('');
				password.val('');
				registerOverlay.modal('hide');

				alert('Success!');
			} else {
				alert(data.errorMessage);
			}
		});
	});

	var signOutButton = $('button.sign-out');
	signOutButton.on('click', function(event) {
		event.preventDefault();
		event.stopPropagation();

		$.ajax({
			url:  signOutButton.attr('data-action'),
			type: 'POST'
		}).done(function(data) {
			if (data.success) {
				location.reload();
			} else {
				alert(data.errorMessage);
			}
		});
	});

	(function(d, s, id) {
		var js;
		var fjs = d.getElementsByTagName(s)[0];
		if (d.getElementById(id)) {return;}
		js = d.createElement(s); js.id = id;
		js.src = '//connect.facebook.net/en_US/sdk.js';
		fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));
});
