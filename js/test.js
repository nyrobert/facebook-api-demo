/* jshint browser:true, jquery:true */
/* global require */
require(['jquery', 'bootstrap'], function($){
	'use strict';

	console.log('test module loaded');

	$(function() {
		$('.register-modal').modal('show');
	});
});
