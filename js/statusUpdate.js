/* jshint browser: true, jquery: true, devel: true */
/* global define */
define(['jquery'], function($) {
	'use strict';

	var statusUpdateContainer = $('.status-update');
	var status                = $('#status');

	function init() {
		statusUpdateContainer
			.find('button.btn-status-update')
			.on('click', statusUpdate);
	}

	function statusUpdate(event) {
		event.preventDefault();

		if (!status.val()) {
			return;
		}

		$.ajax({
			url:      statusUpdateContainer.attr('action'),
			type:     'POST',
			dataType: 'json',
			data: {
				status: status.val()
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
		status.val('');

		alert('Success!');
	}

	return {
		init: init
	};
});
