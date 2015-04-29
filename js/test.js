/* jshint browser: true, jquery: true, devel: true */
/* global define */
define(['jquery', 'bootstrap'], function($){
	'use strict';

	console.log('test module loaded');

	$(function() {
		$('.register-modal').modal('show');
	});
});
