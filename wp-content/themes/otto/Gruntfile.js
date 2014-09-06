module.exports = function(grunt) {
	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),

		// compass 
		compass: { 
			dev: {
				options: {
					sassDir: 'sass',
					cssDir: 'css',
					outputStyle: 'nested'
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

		watch: {
			css: {
				files: '**/*.scss',
				tasks: ['compass', 'autoprefixer']
			}
		}
	});
	grunt.loadNpmTasks('grunt-autoprefixer');
	grunt.loadNpmTasks('grunt-contrib-compass');
	grunt.loadNpmTasks('grunt-contrib-sass');
	grunt.loadNpmTasks('grunt-contrib-watch');
	grunt.registerTask('default',['compass', 'autoprefixer', 'watch']);
}