var Encore = require('@symfony/webpack-encore');

Encore
    .enableSingleRuntimeChunk()
    .setOutputPath('public/build/')
    .setPublicPath('/build')
    .addEntry('js/app', [
        './build/js/app.js',
        './node_modules/jquery/dist/jquery.js',
        './node_modules/popper.js/dist/umd/popper.js',
        './node_modules/bootstrap/dist/js/bootstrap.js',
    ])
    .addStyleEntry('css/app', [
        './node_modules/bootstrap/dist/css/bootstrap.min.css',
        './build/css/app.scss'
    ])
    .autoProvidejQuery()
;
Encore
    .enableSassLoader()
;

module.exports = Encore.getWebpackConfig();