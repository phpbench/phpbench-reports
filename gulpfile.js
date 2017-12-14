var gulp = require('gulp');

gulp.task('default', function(){
  return gulp.src([
      'node_modules/semantic-ui-css/**.min.css',
      'node_modules/chart.js/dist/*.min.js',
      'node_modules/chartjs-plugin-annotation/*.min.js'
  ]).pipe(gulp.dest('public/assets'))
});
