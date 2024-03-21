'use strict';
const { _compress } = require("./gulpfile-compress");

async function _default(){
    console.log('  gulp compress');
}
exports.default = _default;

exports.compress     = _compress;
