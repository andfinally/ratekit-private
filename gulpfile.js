var gulp = require('gulp');
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');
var cssnano = require('gulp-cssnano');

var js = ['ratekit/js/star-rating.js', 'ratekit/js/ratekit.js'];
var css = ['ratekit/css/bootstrap-parts.css', 'ratekit/css/star-rating.css', 'ratekit/css/ratekit.css']

// Minify the CSS file to ratekit-plugin.min.css
gulp.task('css', function() {
    return gulp
        .src(css)
		.pipe(concat('ratekit.min.css'))
        .pipe(cssnano())
        .pipe(gulp.dest('./ratekit/css'));
});

// Bundle and minify the JS files to ratekit-plugin.min.js
gulp.task('js', function() {
    return gulp.src(js)
        .pipe(concat('ratekit.min.js'))
        .pipe(uglify())
        .pipe(gulp.dest('./ratekit/js'));
});

gulp.task('default', ['css', 'js']);
