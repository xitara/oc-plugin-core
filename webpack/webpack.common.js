const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const CopyWebpackPlugin = require('copy-webpack-plugin');
const CompressionPlugin = require('compression-webpack-plugin');
const BrotliPlugin = require('brotli-webpack-plugin');
const paths = require('./paths');

module.exports = {
    context: paths.src,
    entry: {
        app: `./js/app.js`,
        compact: `./scss/compact.scss`,
    },
    output: {
        // filename: `assets/js/[name].[hash:8].js`,
        filename: `assets/js/[name].js`,
        path: paths.build,
    },
    module: {
        rules: [{
                test: /\.js$/,
                exclude: /node_modules/,
                loader: 'babel-loader',
            },
            {
                test: /\.(sa|sc|c)ss$/,
                use: [
                    MiniCssExtractPlugin.loader,
                    {
                        loader: 'css-loader',
                        options: {
                            importLoaders: 2,
                            url: false,
                            sourceMap: true,
                        },
                    },
                    {
                        loader: 'postcss-loader',
                        options: {
                            sourceMap: true,
                            postcssOptions: {
                                plugins: [
                                    require('autoprefixer'),
                                    require('postcss-flexbugs-fixes'),
                                ],
                                processCssUrls: false,
                            },
                        },
                    },
                    {
                        loader: 'sass-loader',
                        options: {
                            sourceMap: true,
                        },
                    },
                ],
            },
            {
                test: /\.html$/,
                use: 'html-loader',
            },
            {
                test: /\.(woff2?|eot|ttf|otf)$/,
                use: {
                    loader: 'file-loader',
                    options: {
                        publicPath: 'assets/fonts',
                        outputPath: 'assets',
                        // name: '[name].[hash:8].[ext]',
                        name: '[path][name].[ext]',
                        esModule: false,
                    },
                },
            },
            {
                test: /\.(gif|ico|jpe?g|png|svg|webp)$/,
                use: {
                    loader: 'file-loader',
                    options: {
                        publicPath: 'assets/images',
                        outputPath: 'assets',
                        // name: '[name].[hash:8].[ext]',
                        name: '[path][name].[ext]',
                        esModule: false,
                    },
                },
            },
        ],
    },
    plugins: [
        new MiniCssExtractPlugin({
            // filename: 'assets/css/[name].[hash:8].css',
            filename: 'assets/css/[name].css',
            chunkFilename: 'assets/css/[name].[id].css',
        }),
        new CopyWebpackPlugin({
            patterns: [
                { from: paths.static },
                // { from: paths.icons, to: 'assets/images/_icons'},
            ],
        }),
        new CompressionPlugin({
            exclude: /\.yaml/,
        }),
        new BrotliPlugin({
            asset: '[path].br[query]',
            test: /\.(js|css|html|svg)$/,
            threshold: 10240,
            minRatio: 0.8
        }),
        // new LiveReloadPlugin(),
    ],
};
