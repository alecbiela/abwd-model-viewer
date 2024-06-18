module.exports = function (grunt) {
	'use strict';

    const sass = require('sass');
    require('load-grunt-tasks')(grunt);

	// Project configuration.
	grunt.initConfig({
		// Read settings from package.json
		pkg: grunt.file.readJSON('package.json'),
		// Paths settings
		dirs: {
			src: {
                sass: './sass',
				js: './js'
			},
			dest: {
				css: '../abwd_model_viewer/css',
				js: '../abwd_model_viewer/js'
			}
		},
		// Check that all JS files conform to our `.jshintrc` files
		jshint: {
			options: {
				jshintrc: true
			},
			target: {
				src: '<%= dirs.src.js %>/**/*.js'
			}
		},
        sass: {
			options: {
                implementation: sass,
				outputStyle: 'compressed',
				sourceMap: false	//change to true for development
			},
			primary: {
				src: '<%= dirs.src.sass %>/main.scss',
				dest: '<%= dirs.dest.css %>/viewer.min.css'
			},
			backport: {
				src: '<%= dirs.src.sass %>/version8-backport.scss',
				dest: '<%= dirs.dest.css %>/version8-backport.min.css'
			}
		},
		// Combine all JS files into one compressed file (including sub-folders)
		uglify: {
			options: {
				banner: '/*! Copyright (C) 2024 Alec Bielanos ' +
					'<%= grunt.template.today("dd-mm-yyyy") %> */\n',
				compress: true,
				mangle: true,
				sourceMap: false //change to true for development
			},
			primary: {
				src: ['<%= dirs.src.js %>/main.js'],
				dest: '<%= dirs.dest.js %>/viewer.min.js'
			},
			backport: {
				src: ['<%= dirs.src.js %>/version8-backport.js'],
				dest: '<%= dirs.dest.js %>/version8-backport.min.js'
			}
		},
		// Trigger relevant tasks when the files they watch has been changed
		// This includes adding/deleting files/folders as well
		watch: {
			configs: {
				options: {
					reload: true
				},
				files: ['Gruntfile.js', 'package.json']
			},
			css: {
				files: '<%= dirs.src.sass %>/**/*.scss',
				tasks: ['build-css']
			},
			js: {
				files: '<%= dirs.src.js %>/**/*.js',
				tasks: ['build-js']
			}
		}
	});

	// Setup build tasks aliases
	grunt.registerTask('build-js', ['jshint', 'uglify:primary', 'uglify:backport']);
	grunt.registerTask('build-css', ['sass:primary', 'sass:backport']);
	grunt.registerTask('build', ['build-js', 'build-css']);

	// Default task(s).
	grunt.registerTask('default', ['build']);
};