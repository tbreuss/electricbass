// See https://stevenwestmoreland.com/2018/01/how-to-include-bootstrap-in-your-project-with-webpack.html

var path = require('path');

module.exports = {
  mode: 'production',
  entry: './bootstrap5/app.js',
  output: {
    filename: 'bootstrap5.js',
    path: path.resolve(__dirname, 'assets')
  },
  module: {
    rules: [
      {
        test: /\.(scss)$/,
        use: [
          {
            // inject CSS to page
            loader: 'style-loader'
          }, {
            // translates CSS into CommonJS modules
            loader: 'css-loader'
          }, {
            // Run postcss actions
            loader: 'postcss-loader',
            options: {
              // `postcssOptions` is needed for postcss 8.x;
              // if you use postcss 7.x skip the key
              postcssOptions: {
                // postcss plugins, can be exported to postcss.config.js
                plugins: function () {
                  return [
                    require('autoprefixer')
                  ];
                }
              }
            }
          }, {
            // compiles Sass to CSS
            loader: 'sass-loader'
          }
        ]
      }
    ]
  }
}
