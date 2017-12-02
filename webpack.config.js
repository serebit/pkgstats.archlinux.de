var Encore = require('@symfony/webpack-encore')

Encore
  .setOutputPath('public/build/')
  .setPublicPath('/build')
  .cleanupOutputBeforeBuild()
  .addEntry('js/package', './assets/js/package.js')
  .addEntry('js/module', './assets/js/module.js')
  .createSharedEntry('js/vendor', [
    'jquery',
    'popper.js',
    'bootstrap',
    'datatables.net',
    'datatables.net-bs4'
  ])
  .addStyleEntry('css/app', './assets/css/app.scss')
  .addStyleEntry('images/archicon', './assets/images/archicon.svg')
  .addStyleEntry('images/archlogo', './assets/images/archlogo.svg')
  .enableSassLoader()
  .enableSourceMaps(!Encore.isProduction())
  .enableVersioning(Encore.isProduction())
  .enablePostCssLoader()
  .autoProvidejQuery()
  .autoProvideVariables({
    'Popper': 'popper.js'
  })

module.exports = Encore.getWebpackConfig()
