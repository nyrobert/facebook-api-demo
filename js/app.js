/* jshint browser:true, jquery:true */
$(function() {
	'use strict';

	var signInOverlay = $('.sign-in-modal');
	signInOverlay.find('button.btn-sign-in').on('click', function(event) {
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

	var signOutButton = $('button.btn-sign-out');
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
});
