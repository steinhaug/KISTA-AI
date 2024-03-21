'use strict';

const { src, dest, series, parallel, watch } = require('gulp');
var debug = require('gulp-debug');
var del                 = require('del');

const { _compress } = require("./gulpfile-compress");


async function _default(){
    console.log('  gulp compress');
}
exports.default = _default;

exports.compress     = _compress;
