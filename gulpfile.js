var gulp = require('gulp');

gulp.task('default', function(){
  return gulp.src([
      'node_modules/semantic-ui-css/**.min.css',
      'node_modules/semantic-ui-css/**.min.js',
      'node_modules/semantic-ui-css/**themes/**',
      'node_modules/jquery/dist/jquery.min.js',
      'node_modules/c3/*.min.*',
      'node_modules/d3/*.min.*',
  ]).pipe(gulp.dest('public/assets'))
});
