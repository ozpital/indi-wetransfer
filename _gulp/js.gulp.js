module.exports = (gulp, options, plugins) => {
    gulp.task('js', gulp.series(
        gulp.parallel('js-browserify')
    ));

    gulp.task('js-browserify', () => {
        const browserify = plugins.browserify({
            entries: process.env.JS_SRC + 'script.js',
            debug: false,
            transform: [plugins.babelify]
        });

        return browserify.bundle()
            .pipe(plugins.vinylSource('script.js'))
            .pipe(plugins.vinylBuffer())
            .pipe(gulp.dest(process.env.JS_DEST))
            .on('error', plugins.log.error);
    });

    gulp.task('js-uglify', () => {
        return gulp.src(process.env.JS_DEST + 'script.js')
            .pipe(plugins.uglify())
            .pipe(gulp.dest(process.env.JS_DEST))
            .on('error', plugins.log.error);
    });
};
