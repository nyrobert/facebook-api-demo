/* jshint browser: true, jquery: true, devel: true */
/* global define */
define(['jquery', 'bootstrap'], function($) {
	'use strict';

	var registerOverlay = $('.register-modal');
	registerOverlay.find('button.btn-register').on('click', function(event) {
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
});
