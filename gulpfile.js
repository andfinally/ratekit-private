var gulp = require('gulp');
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');
var cssnano = require('gulp-cssnano');

var js = ['./ratekit-plugin/js/star-rating.js', './ratekit-plugin/js/ratekit-plugin.js'];
var css = ['./ratekit-plugin/css/bootstrap-parts.css', './ratekit-plugin/css/star-rating.css', './ratekit-plugin/css/ratekit-plugin.css']

// Minify the CSS file to ratekit-plugin.min.css
gulp.task('css', function() {
    return gulp
        .src(css)
		.pipe(concat('ratekit-plugin.min.css'))
        .pipe(cssnano())
        .pipe(gulp.dest('./ratekit-plugin/css'));
});

// Bundle and minify the JS files to ratekit-plugin.min.js
gulp.task('js', function() {
    return gulp.src(js)
        .pipe(concat('ratekit-plugin.min.js'))
        .pipe(uglify())
        .pipe(gulp.dest('./ratekit-plugin/js'));
});

gulp.task('default', ['css', 'js']);
