var gulp = require('gulp');
var uglify = require('gulp-uglify');
var concat = require('gulp-concat');
var cssMin = require('gulp-css');

gulp.task('css', function() {
    gulp.src([
            './lib/bootstrap3/bootstrap.css',
            './lib/fontawesome5/css/all.css',
            './css/all.css',
            './lib/slick/slick.css',
            './lib/fancybox/jquery.fancybox.min.css'
        ]).pipe(concat('app.min.css'))
        .pipe(cssMin())
        .pipe(gulp.dest('./css'));
});
gulp.task('scripts', function() {
    gulp.src([
            './lib/jquery/jquery.min.js',
            './lib/jquery/jquery-migrate.min.js',
            './lib/bootstrap3/bootstrap.js',
            './lib/slick/slick.js',
            './lib/fancybox/jquery.fancybox.min.js',
            './js/frontend.js'
        ])
        .pipe(concat('app.min.js'))
        .pipe(uglify())
        .pipe(gulp.dest('./js'))
});

gulp.task('default', ['css', 'scripts']);