var Encore = require('@symfony/webpack-encore');

Encore
    .enableSingleRuntimeChunk()
    // directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // public path used by the web server to access the output path
    .setPublicPath('/build')

    .addEntry('js/app', './build/js/app.js')
    .addStyleEntry('css/app', ['./build/css/index.scss'])
    .autoProvidejQuery()
;
Encore
    // processes files ending in .scss or .sass
    .enableSassLoader()


;

module.exports = Encore.getWebpackConfig();