'use strict';

var gulp         = require('gulp');
var watch        = require('gulp-watch');
var concat       = require('gulp-concat');
var sass         = require('gulp-sass');
var rename       = require('gulp-rename');
var autoprefixer = require('gulp-autoprefixer');
var lec          = require('gulp-line-ending-corrector');

var paths = {
	scss: ['core/assets/css/scss/**/*.scss', 'core/assets/css/'],
	js: ['core/assets/js/init.js', 'core/assets/js/*.lib.js'],
	scss_wpeo_update_manager: ['modules/wpeo-update-manager/assets/css/scss/**/*.scss', 'modules/wpeo-update-manager/assets/css/'],
	scss_wpeo_upload: ['modules/wpeo-upload/assets/css/scss/**/*.scss', 'modules/wpeo-upload/assets/css/'],
};

// SCSS Core
gulp.task( 'build_scss', function() {
	return gulp.src( paths.scss[0] )
		.pipe( sass( { 'outputStyle': 'expanded' } ).on( 'error', sass.logError ) )
		.pipe( autoprefixer({
			browsers: ['last 2 versions'],
			cascade: false
		}) )
		.pipe(lec({verbose:true, eolc: 'CRLF', encoding:'utf8'}))
		.pipe( gulp.dest( paths.scss[1] ) )
		.pipe( sass({outputStyle: 'compressed'}).on( 'error', sass.logError ) )
		.pipe( rename( './style.min.css' ) )
		.pipe(lec({verbose:true, eolc: 'CRLF', encoding:'utf8'}))
		.pipe( gulp.dest( paths.scss[1] ) );
});

// SCSS Wpeo Update Manager
gulp.task( 'build_scss_wpeo_update_manager', function() {
	return gulp.src( paths.scss_wpeo_update_manager[0] )
		.pipe( sass( { 'outputStyle': 'expanded' } ).on( 'error', sass.logError ) )
		.pipe( autoprefixer({
			browsers: ['last 2 versions'],
			cascade: false
		}) )
		.pipe(lec({verbose:true, eolc: 'CRLF', encoding:'utf8'}))
		.pipe( gulp.dest( paths.scss_wpeo_update_manager[1] ) )
		.pipe( sass({outputStyle: 'compressed'}).on( 'error', sass.logError ) )
		.pipe( rename( './style.min.css' ) )
		.pipe(lec({verbose:true, eolc: 'CRLF', encoding:'utf8'}))
		.pipe( gulp.dest( paths.scss_wpeo_update_manager[1] ) );
});

// SCSS Wpeo Upload
gulp.task( 'build_scss_wpeo_upload', function() {
	return gulp.src( paths.scss_wpeo_upload[0] )
		.pipe( sass( { 'outputStyle': 'expanded' } ).on( 'error', sass.logError ) )
		.pipe( autoprefixer({
			browsers: ['last 2 versions'],
			cascade: false
		}) )
		.pipe(lec({verbose:true, eolc: 'CRLF', encoding:'utf8'}))
		.pipe( gulp.dest( paths.scss_wpeo_upload[1] ) )
		.pipe( sass({outputStyle: 'compressed'}).on( 'error', sass.logError ) )
		.pipe( rename( './style.min.css' ) )
		.pipe(lec({verbose:true, eolc: 'CRLF', encoding:'utf8'}))
		.pipe( gulp.dest( paths.scss_wpeo_upload[1] ) );
});

gulp.task('build_js', function() {
	return gulp.src(paths.js)
		.pipe(concat('wpeo-assets.js'))
		.pipe(gulp.dest('js/dest/'))
})

gulp.task( 'default', function() {
	gulp.watch( paths.scss[0], gulp.series('build_scss') );
	gulp.watch( paths.scss_wpeo_update_manager[0], gulp.series('build_scss_wpeo_update_manager') );
	gulp.watch( paths.scss_wpeo_upload[0], gulp.series('build_scss_wpeo_upload') );
	gulp.watch( paths.js, gulp.series('build_js') );
});
