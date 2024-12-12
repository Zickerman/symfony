const Encore = require('@symfony/webpack-encore');

Encore
  .setOutputPath('public/build/')
  .setPublicPath('/build')
  .addEntry('js/app', './assets/js/app.js')
  .addEntry('bootstrap', './assets/bootstrap.js')
  .enableVueLoader()
  .enableSingleRuntimeChunk()
  .enableSassLoader();

module.exports = Encore.getWebpackConfig();
