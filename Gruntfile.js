module.exports = function(grunt) {
      // require('load-grunt-tasks')(grunt); //自动加载grunt任务


          grunt.initConfig({
             //读取package.json的内容，形成个json数据
             pkg: grunt.file.readJSON('package.json'),
             concat: {
              options: {
                banner: '/*! @<%= pkg.name %> Date: <%= grunt.template.today("yyyy-mm-dd, h:MM:ss TT") %> */\n',
              }
            },

            connect: {
                options: {
                    port: 8000,
                    hostname: '*',
                    livereload: 35729, // 端口不能变
                },
                server: {
                    options: {
                        open: true,
                        base: [
                        // 实时预览路径
                            './../../'
                        ],
                        middleware: [
                          function myMiddleware(req, res, next) {
                            // 设置项目启动的默认路径
                           res.end('<script>window.onload=function(){window.location.href="http://localhost/jiuhuar/pc-en/distribution-web/static/page/"}</script>');
                          }
                        ],
                    }
                }
            },


            /* css */
            less: {

              development: {
                options: {
                  compress: false,
                  yuicompress: false,
                },
                files: {
                  "static/css/index.css": "static/less/_page/index.less",
                  "static/css/common.css": "static/less/_page/common.less",
                  // util一般不用编译,默认编译一次就可以了
                  //"static/css/base/util.css": "static/less/base/util.less",

                }
              },
            },
            postcss: {
              options: {
                processors: [
                  require('autoprefixer')({browsers: ['last 2 versions']}),
                  // require('cssnano'),
                  require('cssgrace'),
                ]
              },
              dist : {
                    src: ['static/css/*.css']
                    
              } 
            },
            /* images*/
            sprite: {
                all: {
                    src: ['static/images/sprites/*'],
                    dest: 'static/images/sprites.png',
                    destCss: 'static/less/sprites.less',
                    algorithm: 'top-down',
                    padding: 5
                }
            },

            watch: {
              options: {
                  //显示日志
                  dateFormate: function(time) {
                      grunt.log.writeln('编译完成,用时' + time + 'ms ' + (new Date()).toString());
                      grunt.log.writeln('Wating for more changes...');
                  }
              },

              grunt: {
                files: ['Gruntfile.js']
              },

              // 选择样式执行 less autoprefixer
              styles: {
                files: [
                        'static/**/*.less',
                        'static/**/**/*.less',
                        'static/**/**/**/*.less'
                ],
                tasks: [
                        'less:development'
                        ///'postcss',
                ],
                options: {
                  nospawn: true,
                  livereload: true
                }
              },
              sprite: {
                files: ['static/images/{,*/}*'],
                tasks: ['sprite']
               },
              // 选择文件执行实时刷新
              livereload: {
                  options: {
                      livereload: '<%=connect.options.livereload%>' //监听前面声明的端口  35729
                  },

                  files: [
                      // 监视文件变化
                      'static/page/**/*.php',
                      'static/js/**/*.js',
                  ]
              }
            }


          });

         //输出进度日志
          grunt.event.on('watch', function(action, filepath, target) {
              grunt.log.writeln(target + ': ' + '文件: ' + filepath + ' 变动状态: ' + action);
          });

          grunt.loadNpmTasks('grunt-contrib-connect');
          grunt.loadNpmTasks('grunt-contrib-concat');
          grunt.loadNpmTasks('grunt-contrib-less');
          grunt.loadNpmTasks('grunt-contrib-watch');
          grunt.loadNpmTasks('grunt-postcss');
          grunt.loadNpmTasks('grunt-spritesmith');


          grunt.registerTask('default', ['connect:server','watch','postcss']);

        };
