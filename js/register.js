/* jshint browser: true, jquery: true, devel: true */
/* global define */
define(['jquery', 'bootstrap'], function($) {
	'use strict';

	var registerOverlay = $('.register-modal');
	var email           = $('#register-email');
	var password        = $('#register-password');

	function init() {
		registerOverlay.find('button.btn-register').on('click', register);
	}

	function register(event) {
		event.preventDefault();

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
				success();
			} else {
				alert(data.errorMessage);
			}
		});
	}

	function success() {
		email.val('');
		password.val('');
		registerOverlay.modal('hide');

		alert('Success!');
	}

	return {
		init: init
	};
});
