module.exports = function(grunt) {

	// load all grunt tasks matching the `grunt-*` pattern
	require('load-grunt-tasks')(grunt);

	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),


		// compass
		compass: {
			dev: {
				options: {
					sassDir: 'sass',
					cssDir: 'css',
					outputStyle: 'compressed'
				}
			}
		},

		// autoprefixer
		autoprefixer: {
			options: {
				browsers: ['last 2 versions', 'ie 9', 'ios 6', 'android 4'],
				map: true
			},
			files: {
				expand: true,
				flatten: true,
				src: 'css/*.css',
				dest: 'css'
			},
		},

		// image optimization
		imagemin: {
			dist: {
				options: {
					optimizationLevel: 7,
					progressive: true,
					interlaced: true
				},
				files: [{
					expand: true,
					cwd: 'img/',
					src: ['**/*.{png,jpg,gif}'],
					dest: 'img/'
				}]
			}
		},

		watch: {
			css: {
				files: '**/*.scss',
				tasks: ['compass', 'autoprefixer']
			},
			images: {
				files: ['img/*.{png,jpg,gif}'],
				tasks: ['imagemin']
			},
			livereload: {
				options: { livereload: true },
				files: ['/css/styles.css', 'js/*.js', 'img/*.{png,jpg,jpeg,gif,webp,svg}']
			}
		}
	});

	grunt.registerTask('default',['compass', 'autoprefixer', 'imagemin', 'watch']);
}
