'use strict';

const { src, dest, watch, series } = require("gulp");

const autoprefixer = require("gulp-autoprefixer");
const concat = require("gulp-concat");
const imagemin = require("gulp-imagemin");
const minify = require("gulp-minify");
const newer = require("gulp-newer");
const plumber = require("gulp-plumber");
const rename = require("gulp-rename");
const sass = require('gulp-sass')(require('sass'));
const sourcemaps = require("gulp-sourcemaps");
const livereload = require('gulp-livereload');

const inputDir = "./resources";
const outputDir = "./public";

// SCSS to CSS
function sassToCss() {
    return src(inputDir + "/scss/*.*")
        .pipe(sourcemaps.init())
        .pipe(plumber())
        .pipe(
            sass({
                outputStyle: "expanded"
            })
        )
        .pipe(autoprefixer())
        .pipe(
            rename({
                extname: ".css"
            })
        )
        .pipe(sourcemaps.write("./"))
        .pipe(dest(outputDir + "/css"))
        .pipe(livereload());
}

function cssMini() {
    return src([outputDir + "/css/*.css", "!" + outputDir + "/css/*.min.css"])
        .pipe(sourcemaps.init())
        .pipe(
            rename({
                suffix: ".min"
            })
        )
        .pipe(dest(outputDir + "/css"))
        .pipe(livereload());
}

// Optimize Images
function images() {
    return src(inputDir + "/img/**/*")
        .pipe(newer("public/img"))
        .pipe(
            imagemin()
        )
        .pipe(dest(outputDir + "/img"))
        .pipe(livereload());
}

// Concat JS Scripts
function concatJs() {
    return src([inputDir + "/js/application.js", inputDir + "/js/auth.js"])
        //        .pipe(concat("application.js"))
        .pipe(dest(outputDir + "/js"))
        .pipe(livereload());
}

// Compress JS Script
function compressJsScripts() {
    return src([outputDir + "/js/application.js", outputDir + "/js/auth.js"])
        .pipe(
            minify({
                ext: {
                    min: ".min.js"
                },
                ignoreFiles: ["*min.js"]
            })
        )
        .pipe(dest(outputDir + "/js"))
        .pipe(livereload());
}

// Watch the PHP files
function reloadBrowser() {
    return src("./*").pipe(livereload());
}

// Watch files and run tasks
function watchFiles() {
    "use strict";
    livereload.listen();
    watch("./app/**/*.twig", reloadBrowser);
    watch("./app/**/*.php", reloadBrowser);
    watch("./app/**/*.php", reloadBrowser);
    watch(inputDir + "/scss/**/*", series(sassToCss, cssMini));
    watch(inputDir + "/js/**/*", series(concatJs, compressJsScripts));
    watch(inputDir + "/img/**/*", images);
}

exports.sassToCss = sassToCss;
exports.cssMini = cssMini;
exports.images = images;
exports.concatJs = concatJs;
exports.compressJsScripts = series(concatJs, compressJsScripts);

exports.watch = watchFiles;
exports.default = series(sassToCss, cssMini, images, concatJs, compressJsScripts);
