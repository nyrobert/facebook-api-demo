/* jshint browser: true, jquery: true, devel: true */
/* global define */
define(['jquery', 'bootstrap'], function($) {
	'use strict';

	var signInOverlay = $('.sign-in-modal');
	var signOutButton = $('button.btn-sign-out');
	var email         = $('#sign-in-email');
	var password      = $('#sign-in-password');

	function init() {
		signInOverlay.find('button.btn-sign-in').on('click', login);
		signOutButton.on('click', logout);
	}

	function login(event) {
		stopEvent(event);

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
				success();
			} else {
				failure(data.errorMessage);
			}
		});
	}

	function logout(event) {
		stopEvent(event);

		$.ajax({
			url:  signOutButton.attr('data-action'),
			type: 'POST'
		}).done(function(data) {
			if (data.success) {
				success();
			} else {
				failure(data.errorMessage);
			}
		});
	}

	function stopEvent(event) {
		event.preventDefault();
		event.stopPropagation();
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
