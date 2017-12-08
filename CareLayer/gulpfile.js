var gulp            = require('gulp');

var source          = require('vinyl-source-stream');
var browserify      = require('browserify');
var preprocessify   = require('preprocessify');
var nodemon         = require('gulp-nodemon');
var gulpSequence    = require('gulp-sequence');

var env           = 'dev';

gulp.task('html', function() 
{
  return gulp.src(['src/*.html'])
    .pipe(gulp.dest('dist'));
});
                   
gulp.task('styles', function() 
{
  return gulp.src(['src/styles/*'])
    .pipe(gulp.dest('dist/styles'));
});
                   
gulp.task('fonts', function() 
{
  return gulp.src(['src/assets/fonts/*'])
    .pipe(gulp.dest('dist/assets/fonts'));
});
                   
gulp.task('images', function() 
{
  return gulp.src(['src/assets/images/*'])
    .pipe(gulp.dest('dist/assets/images'));
});

gulp.task('server', function()
{
    return gulp.src(['src/server.js'])
    .pipe(gulp.dest('dist'));
});

gulp.task('modules', function()
{
    return gulp.src(['src/modules/*'])
    .pipe(gulp.dest('dist/modules'));
});

gulp.task('scripts', function()
{
    var extensions = ['.jsx'];
    
    return browserify(
    {
        entries: ['./src/scripts/app.js'],
        extensions: extensions,
        debug: env === 'dev'
    })
    .transform(preprocessify(
        {
            env: env,
        }, 
        {
            includeExtensions: extensions
        }
    ))
    .transform('reactify')
    .bundle()
    .pipe(source('app.js'))
    .pipe(gulp.dest('./dist/scripts'));
});

gulp.task('start', function()
{
    gulpSequence(['server', 'modules']);
    nodemon(
    {
        script: './dist/server.js',
        ext: 'js',
        ignore: ['src/*', 'dist/scripts/*'],
        env: { 'NODE_ENV': 'development' }
    });
});

gulp.task('watch', function()
{
    gulp.watch(['src/scripts/**/*.js', 'src/scripts/**/*.jsx'], ['scripts']);
    gulp.watch(['src/*.html'], ['html']);
    gulp.watch(['src/styles/*'], ['styles']);
    gulp.watch(['src/assets/fonts/*'], ['fonts']);
    gulp.watch(['src/assets/images/*'], ['images']);
    gulp.watch(['src/server.js'], ['server']);
    gulp.watch(['src/modules/*'], ['modules']);
});

gulp.task('default', gulpSequence(['html', 'styles', 'fonts', 'images', 'scripts', 'server', 'modules'], 'watch', 'start'));
