/* jshint browser: true, jquery: true, devel: true */
/* global define, FB, config */
define(['jquery', 'facebooksdk'], function($, FB) {
	'use strict';

	var permissions = {
		publicProfile:  'public_profile',
		email:          'email',
		publishActions: 'publish_actions'
	};

	var loginButton           = $('button.btn-facebook-login');
	var disconnectButton      = $('button.btn-facebook-disconnect');
	var statusUpdateContainer = $('.status-update');

	function init() {
		FB.init({
			appId:   config.appId,
			cookie:  true,
			version: 'v2.3'
		});

		loginButton.on('click', login);
		disconnectButton.on('click', disconnect);
		statusUpdateContainer.find('.btn-status-update')
			.on('click', statusUpdate);
	}

	function login(event) {
		event.preventDefault();

		var requiredPermissions = [permissions.publicProfile, permissions.email];

		FB.login(function(response) {
			loginCallback(
				'login',
				loginButton.attr('data-login-url'),
				requiredPermissions,
				response
			);
		}, {scope: requiredPermissions.join(',')});
	}

	function disconnect(event) {
		event.preventDefault();

		var requiredPermissions = permissions.publicProfile;

		FB.login(function(response) {
			loginCallback(
				'disconnect',
				disconnectButton.attr('data-disconnect-url'),
				requiredPermissions,
				response
			);
		}, {scope: requiredPermissions});
	}

	function statusUpdate(event) {
		event.preventDefault();

		var requiredPermissions = [
			permissions.publicProfile,
			permissions.email,
			permissions.publishActions
		];

		FB.login(function(response) {
			loginCallback(
				'statusUpdate',
				statusUpdateContainer.attr('action'),
				requiredPermissions,
				response
			);
		}, {scope: requiredPermissions.join(',')});
	}

	function reAskPermission(type, url, declinedPermissions) {
		FB.login(function(response) {
			loginCallback(type, url, declinedPermissions, response);
		}, {
			'scope':     declinedPermissions.join(','),
			'auth_type': 'rerequest'
		});
	}

	function loginCallback(type, url, requiredPermissions, response) {
		if (response.status === 'connected') {
			handleDeclinedPermissions(type, url, requiredPermissions, saveHandler);
		} else if (response.status === 'not_authorized') {
			failure('Please log into this app.');
		} else {
			failure('Please log into Facebook.');
		}
	}

	function handleDeclinedPermissions(
		type, url, requiredPermissions, saveHandler
	) {
		FB.api('/me/permissions', function(response) {
			var declinedPermissions = [];

			response.data.forEach(function(element) {
				if (
					requiredPermissions.indexOf(element.permission) > -1 &&
					element.status === 'declined'
				) {
					declinedPermissions.push(element.permission);
				}
			});

			if (declinedPermissions.length) {
				if (
					confirm(
						'The following permissions are required: ' +
						declinedPermissions.join(',')
					)
				) {
					reAskPermission(type, url, declinedPermissions);
				}
			} else {
				saveHandler(type, url);
			}
		});
	}

	function saveHandler(type, url) {
		if (type === 'statusUpdate') {
			$.ajax({
				url:      url,
				type:     'POST',
				dataType: 'json',
				data: {
					message: statusUpdateContainer.find('input[type="text"]').val()
				}
			}).done(function(data) {
				if (data.success) {
					alert('Success.');
				} else {
					failure(data.errorMessage);
				}
			});
		} else {
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
