/* jshint browser: true, jquery: true, devel: true */
/* global define, FB, config */
define(['jquery', 'facebooksdk'], function($, FB) {
	'use strict';

	var defaultScope     = 'public_profile,email';
	var loginButton      = $('button.btn-facebook-login');
	var disconnectButton = $('button.btn-facebook-disconnect');

	function init() {
		FB.init({
			appId:   config.appId,
			cookie:  true,
			version: 'v2.3'
		});

		loginButton.on('click', login);
		disconnectButton.on('click', disconnect);
	}

	function login(event) {
		stopEvent(event);

		FB.login(function(response) {
			callback(loginButton.attr('data-login-url'), response);
		}, {scope: defaultScope});
	}

	function disconnect(event) {
		stopEvent(event);

		FB.login(function(response) {
			callback(disconnectButton.attr('data-disconnect-url'), response);
		}, {scope: defaultScope});
	}

	function callback(url, response) {
		if (response.status === 'connected') {
			$.ajax({
				url:  url,
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

	function stopEvent(event) {
		event.preventDefault();
		event.stopPropagation();
	}

	return {
		init: init
	};
});
