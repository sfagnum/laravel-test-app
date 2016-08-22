var gulp = require('gulp');
var size = require('gulp-size');
var sass = require('gulp-sass');
var concat = require('gulp-concat');
var cssmin = require('gulp-cssmin');
var uglify = require('gulp-uglify');

var config = {
    resDir: 'resources',
    publicDir: 'public',
    assetsDir: 'resources/assets',

    fontsDirs: [
        'node_modules/bootstrap/dist/fonts/**/*.*'
    ],

    jsLibs: [
        'node_modules/angular/angular.js',
        'node_modules/angular-ui-bootstrap/dist/ui-bootstrap.js',
        'node_modules/angular-ui-bootstrap/dist/ui-bootstrap-tpls.js',
        'node_modules/angular-animate/angular-animate.js'
    ],
    cssLibs: [
        'node_modules/bootstrap/dist/css/bootstrap.css',
        'node_modules/angular/angular-csp.css',
        'node_modules/angular-ui-bootstrap/dist/ui-bootstrap-csp.css'
    ]
};

gulp.task('fonts', function() {
    gulp.src(config.fontsDirs)
        .pipe(gulp.dest(config.publicDir + '/fonts'))
        .pipe(size())
});

gulp.task('sass:main', function(){
    gulp.src(config.assetsDir + '/sass/*.scss')
        .pipe(sass().on('error', sass.logError))
        .pipe(gulp.dest(config.publicDir + '/css'))
        .pipe(size())
});

gulp.task('css:lib', function(){
    gulp.src(config.cssLibs)
        .pipe(concat('lib.min.css'))
        .pipe(cssmin())
        .pipe(gulp.dest(config.publicDir + '/css/'))
        .pipe(size())
});

gulp.task('js:lib', function(){
    gulp.src(config.jsLibs)
        .pipe(concat('lib.min.js'))
        .pipe(uglify())
        .pipe(gulp.dest(config.publicDir + '/js'))
        .pipe(size())
});

gulp.task('js:main', function(){
    gulp.src(config.assetsDir + '/js/**/*.js')
        .pipe(concat('app.min.js'))
        .pipe(uglify())
        .pipe(gulp.dest(config.publicDir + '/js'))
        .pipe(size())
});

gulp.task('default', ['fonts', 'sass:main', 'js:main', 'css:lib', 'js:lib']);

gulp.task('watch', function(){
    gulp.watch(config.assetsDir + '/sass/*.scss', ['sass:main']);
    gulp.watch(config.assetsDir + '/js/**/*', ['js:main'])
});