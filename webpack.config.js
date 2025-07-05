const config = require('flarum-webpack-config');
const path = require('path');

const webpackConfig = config();

// Override output path
webpackConfig.output.path = path.resolve(__dirname, 'js/dist');

module.exports = webpackConfig;