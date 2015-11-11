module.exports = function (grunt) {
    require('jit-grunt')(grunt)({
        pluginsRoot: 'node_modules'
    });

    grunt.initConfig({
        less: {
            development: {
                options: {
                    compress: false,
                    yuicompress: false,
                    optimization: 2
                },
                files: {
                    "www/styles/styles.css": "www/styles/less/main.less" // destination file and source file
                }
            }
        },
        nodestatic: {
            server: {
                options: {
                    dev: true,
                    verbose: false,
                    port: 8888,
                    base: '.'
                }
            }
        },
        watch: {
            styles: {
                files: ['www/styles/less/**/*.less'], // which files to watch
                tasks: ['less'],
                options: {
                    nospawn: true
                }
            }
        },
        composer: {
            options: {
                usePhp: false,
                phpArgs: {},
                flags: [],
                cwd: 'api/v1',
                composerLocation: '/usr/bin/composer'
            },
            some_target: {}
        },
        bower: {
            install: {
                options: {
                    targetDir: 'www/bower_components',
                    layout: 'byType',
                    install: true,
                    verbose: false,
                    cleanTargetDir: false,
                    cleanBowerDir: false,
                    bowerOptions: {}
                }
            }
        }
    });

    grunt.registerTask('init', ['composer', 'bower', 'less']);
    grunt.registerTask('default', ['less', 'nodestatic', 'watch']);
    grunt.registerTask('styles', ['less', 'watch']);
};