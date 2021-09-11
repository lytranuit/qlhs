module.exports = function(grunt) {
    grunt.initConfig({
        uglify: {
            gopjs: {
                options: {
                    sourceMap: true
                },
                src: [
                    './lib/jquery/jquery.min.js',
                    './lib/jquery/jquery-migrate.min.js',
                    './lib/bootstrap3/bootstrap.js',
                    './lib/slick/slick.js',
                    './lib/fancybox/jquery.fancybox.min.js',
                    './js/frontend.js'
                ],
                dest: './js/app.min.js'
            },
        },
        cssmin: {
            gopcss: {
                options: {
                    sourceMap: true
                },
                src: [
                    './lib/bootstrap3/bootstrap.css',
                    './lib/fontawesome5/css/all.css',
                    './css/all.css',
                    './lib/slick/slick.css',
                    './lib/fancybox/jquery.fancybox.min.css'
                ],
                dest: './css/app.min.css'
            }
        }
    });

    grunt.loadNpmTasks('grunt-contrib-uglify-es');
    grunt.loadNpmTasks('grunt-contrib-cssmin');

    grunt.registerTask('default', ['uglify', 'cssmin']);
};