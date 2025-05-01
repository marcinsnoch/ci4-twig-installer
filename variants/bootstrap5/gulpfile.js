import { dest, parallel, series, src, watch } from 'gulp';
import autoprefixer from 'gulp-autoprefixer';
import cleanCSS from 'gulp-clean-css';
import concat from 'gulp-concat';
import imagemin from 'gulp-imagemin';
import livereload from 'gulp-livereload';
import newer from 'gulp-newer';
import plumber from 'gulp-plumber';
import rename from 'gulp-rename';
import gulpSass from 'gulp-sass';
import terser from 'gulp-terser';
import imageminGifsicle from 'imagemin-gifsicle';
import imageminMozjpeg from 'imagemin-mozjpeg';
import imageminOptipng from 'imagemin-optipng';
import imageminSvgo from 'imagemin-svgo';
import * as dartSass from 'sass';

const sass = gulpSass(dartSass);

// SCSS to CSS
export function sassToCss() {
    return src('resources/scss/**/*.scss')
        .pipe(plumber())
        .pipe(
            sass({
                outputStyle: 'expanded'
            })
        )
        .pipe(autoprefixer())
        .pipe(dest('public/css'))
        .pipe(livereload());
}

// Minify CSS
export function cssMini() {
    return src(['public/css/*.css', '!public/css/*.min.css'])
        .pipe(cleanCSS({ compatibility: 'ie11' }))
        .pipe(
            rename({
                suffix: '.min'
            })
        )
        .pipe(dest('public/css'))
        .pipe(livereload());
}

// Optimize Images
export function optimizeImages() {
    return src('resources/img/**/*')
        .pipe(newer('public/img'))
        .pipe(
            imagemin([
                imageminGifsicle({ interlaced: true }),
                imageminMozjpeg({ quality: 75, progressive: true }),
                imageminOptipng({ optimizationLevel: 5 }),
                imageminSvgo()
            ])
        )
        .pipe(dest('public/img'))
        .pipe(livereload());
}

// Concat JS Scripts
export function concatJs() {
    return src('resources/js/**/*.js')
        .pipe(plumber())
        .pipe(concat('application.js'))
        .pipe(dest('public/js'))
        .pipe(livereload());
}

// Minify JS Scripts
export function minifyJs() {
    return src(['public/js/*.js', '!public/js/*.min.js'])
        .pipe(terser())
        .pipe(
            rename({
                suffix: '.min'
            })
        )
        .pipe(dest('public/js'))
        .pipe(livereload());
}

// Watch the PHP files
function reloadBrowser() {
    return src("./*").pipe(livereload());
}

// Watch files and run tasks
export function watchFiles() {
    livereload.listen();
    watch("./app/**/*.twig", reloadBrowser);
    watch("./app/**/*.php", reloadBrowser);
    watch("./app/**/*.php", reloadBrowser);
    watch('resources/scss/**/*.scss', series(sassToCss, cssMini));
    watch('resources/js/**/*.js', series(concatJs, minifyJs));
    watch('resources/img/**/*', optimizeImages);
}

// Default task
export default series(
    parallel(sassToCss, optimizeImages, concatJs),
    parallel(cssMini, minifyJs)
);
