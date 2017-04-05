const path = require('path')
const webpack = require('webpack')

const prod = process.env.NODE_ENV === 'production'
const output = path.resolve(__dirname, 'www')

var ExtractTextPlugin = require('extract-text-webpack-plugin')
var HtmlWebpackPlugin = require('html-webpack-plugin')

let config = {
  entry: './src/main.js',
  output: {
    path: output,
    publicPath: '',
    filename: prod ? '[name].[chunkhash].js' : '[name].js'
  },
  module: {
    rules: [{
      test: /\.vue$/,
      loader: 'vue-loader',
      options: {
        loaders: {
          'scss': prod ? ExtractTextPlugin.extract({ use: 'css-loader?minimize=true!sass-loader' }) : 'vue-style-loader!css-loader!sass-loader',
          'sass': prod ? ExtractTextPlugin.extract({ use: 'css-loader?minimize=true!sass-loader?indentedSyntax' }) : 'vue-style-loader!css-loader!sass-loader?indentedSyntax'
        }
      }
    }, {
      test: /\.js$/,
      loader: 'babel-loader',
      exclude: /node_modules/,
      query: {
        presets: ['latest'],
        cacheDirectory: true
      }
    }, {
      test: /\.(png|jpg|gif|svg)$/,
      loader: 'file-loader',
      options: {
        name: '[name].[chunkhash].[ext]'
      }
    }, {
      test: /\.css$/,
      loader: 'style-loader!css-loader'
    }]
  },
  resolve: {
    alias: {
      'vue$': 'vue/dist/vue.esm.js',
      '@': path.resolve(__dirname, 'src')
    },
    extensions: ['.js', '.json', '.vue']
  },
  plugins: [],
  devServer: {
    historyApiFallback: true,
    noInfo: true,
    contentBase: output,
    hot: true,
    proxy: {
      '/api/**': {
        target: 'http://localhost:80/som/www/',
        secure: false,
        changeOrigin: true
      }
    }
  },
  devtool: prod ? '' : 'cheap-source-map'
}

if (prod) {
  // Clean the 'www' folder of previous files
  const glob = require('glob')
  const fs = require('fs')
  glob.glob('./www/*.*', function (er, files) {
    files.forEach(f => {
      fs.unlinkSync(f)
    })
  })

  // Production only plugins
  config.plugins.push(new webpack.optimize.CommonsChunkPlugin({
    name: 'vendor',
    minChunks: function (module) {
      return module.context && module.context.indexOf('node_modules') !== -1
    }
  }))

  config.plugins.push(new webpack.optimize.CommonsChunkPlugin({
    name: 'manifest'
  }))

  config.plugins.push(new webpack.optimize.UglifyJsPlugin({
    sourceMap: true,
    compress: {
      warnings: false
    }
  }))

  config.plugins.push(new ExtractTextPlugin('style.[chunkhash].css'))
}

// Either plugins

config.plugins.push(new HtmlWebpackPlugin({
  template: './src/index.html',
  filename: 'index.html',
  minify: prod ? {
    collapseWhitespace: true,
    removeComments: true
  } : false
}))

module.exports = config
