/* jshint browser: true */
/* global require */
require.config({
	paths: {
		jquery:    '../bower_components/jquery/dist/jquery.min',
		bootstrap: '../bower_components/bootstrap/dist/js/bootstrap.min',
		facebooksdk:  '//connect.facebook.net/en_US/sdk'
	},
	shim: {
		bootstrap: {
			deps: ['jquery']
		},
		facebooksdk: {
			exports: 'FB'
		}
	}
});

require(['register', 'login', 'facebook'], function(register, login, facebook) {
	'use strict';

	register.init();
	login.init();
});
