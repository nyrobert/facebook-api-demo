/* jshint browser:true */
/* global require */
require.config({
	paths: {
		jquery: '../bower_components/jquery/dist/jquery.min.js',
		bootstrap: '../bower_components/bootstrap/dist/js/bootstrap.min.js'
	},
	shim: {
		bootstrap: {
			deps: ['jquery']
		}
	}
});
