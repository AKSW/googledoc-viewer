module.exports = {
    entry: './frontend/entrypoint.js',
    output: {
        filename : './frontend/bundle.js'
    },
  module: {
    loaders: [
        { test: /datatables\.net.*/, loader: 'imports?define=>false'}, 
        { test: /\.js$/, loader: 'babel-loader', query: {
            presets: ['es2015']
            }
        }
    ]
  }
}
