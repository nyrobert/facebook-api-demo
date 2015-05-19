/* jshint browser: true, jquery: true, devel: true */
/* global define, FB, config */
define(['jquery', 'facebooksdk'], function($, FB) {
	'use strict';

	var loginButton = $('button.btn-facebook-login');

	function init() {
		FB.init({
			appId:   config.appId,
			cookie:  true,
			version: 'v2.3'
		});

		loginButton.on('click', login);
	}

	function login(event) {
		event.preventDefault();
		event.stopPropagation();

		FB.login(function(response) {
			loginCallback(response);
		}, {scope: 'public_profile,email'});
	}

	function loginCallback(response) {
		if (response.status === 'connected') {
			$.ajax({
				url:  loginButton.attr('data-login-url'),
				type: 'POST'
			}).done(function(data) {
				if (data.success) {
					success();
				} else {
					failure(data.errorMessage);
				}
			});
		} else if (response.status === 'not_authorized') {
			failure('Please log into this app.');
		} else {
			failure('Please log into Facebook.');
		}
	}

	function success() {
		location.reload();
	}

	function failure(errorMessage) {
		alert(errorMessage);
	}

	return {
		init: init
	};
});
