const path = require('path');
const defaults = require('@wordpress/scripts/config/webpack.config');
const DotEnv = require('dotenv-webpack');
const webpack = require('webpack');
const ESLintPlugin = require('eslint-webpack-plugin');

const DotEnvObject = new DotEnv();

module.exports = (env) => {

  console.log('env', env);

  process.env.PRO_ENABLED = (env.pro) ? true : false;
  console.log('process env values', process.env);

  const plugins = [
    ...defaults.plugins, 
    DotEnvObject, 
    new webpack.DefinePlugin({
      "process.env": JSON.stringify(process.env)
    }),
    /*
    new ESLintPlugin({
      extensions: ['js', 'jsx']
    })
    */
  ];

  return {
    ...defaults,
    "context": __dirname,
    "entry": path.join(__dirname, 'src/script/index.js'),
    output: {
      filename: 'index.js',
      "path": path.join(__dirname, 'build')
    },
    externals: {
      react: 'React',
      'react-dom': 'ReactDOM',
    },
    plugins: plugins
  }
};