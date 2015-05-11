/* jshint browser: true, jquery: true, devel: true */
/* global define */
define(['jquery', 'bootstrap'], function($) {
	'use strict';

	var loginOverlay = $('.login-modal');
	var logoutButton = $('button.btn-logout');
	var email        = $('#login-email');
	var password     = $('#login-password');

	function init() {
		loginOverlay.find('button.btn-login').on('click', login);
		logoutButton.on('click', logout);
	}

	function login(event) {
		stopEvent(event);

		if (!email.val() || !password.val()) {
			return;
		}

		$.ajax({
			url:      loginOverlay.find('form.login').attr('action'),
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
			url:  logoutButton.attr('data-action'),
			type: 'POST'
		}).done(function(data) {
			if (data.success) {
				success();
			} else {
				failure(data.errorMessage);
			}
		});
	}

	function hide() {
		loginOverlay.modal('hide');
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
		init: init,
		hide: hide
	};
});
