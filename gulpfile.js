const gulp = require('gulp');
const uglify = require('gulp-uglify');
const concat = require('gulp-concat');
const debug = require('gulp-debug');
const del = require('del');

/*
import gulp from 'gulp';
import uglify from 'gulp-uglify';
import concat from 'gulp-concat';
import debug from 'gulp-debug';
import del from 'del';
*/

async function _compress () {

    (async () => {
        const deletedPaths = await del([
            'scripts/gallery-controller.js'
        ], {force: true, dryRun: false, onlyFiles:true});
    })();

    return gulp.src([
            'htdocs/scripts/src/_functions/*.js'
        ])
        .pipe(debug())
        .pipe(concat('gallery-controller.js'))
        .pipe(uglify())
        .pipe(gulp.dest('htdocs/scripts'));
}

exports.compress = gulp.series(
  _compress
);