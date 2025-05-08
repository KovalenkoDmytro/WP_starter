const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const CssMinimizerPlugin = require("css-minimizer-webpack-plugin");
const BrowserSyncPlugin = require("browser-sync-webpack-plugin");
const path = require("path");

// Updated paths for dktheme
const baseUrl = "./app/themes/dktheme/src/";
const outputPath = "./app/themes/dktheme/public/";
const localDomain = "http://localhost:8080/";

module.exports = {
    entry: [baseUrl + "style/scss/main.scss", baseUrl + "js/main.js"],
    output: {
        path: path.resolve(__dirname, outputPath),
        filename: "app.js",
    },
    plugins: [
        new MiniCssExtractPlugin({
            filename: "app.css",
        }),
        new BrowserSyncPlugin(
            {
                proxy: localDomain,
                files: [outputPath + "/*.css", "*.php"],
                injectCss: true,
            },
            { reload: false }
        ),
    ],
    module: {
        rules: [
            {
                test: /\.scss$/i,
                use: [
                    MiniCssExtractPlugin.loader,
                    "css-loader",
                    {
                        loader: 'postcss-loader',
                        options: {
                            postcssOptions: {
                                plugins: function () {
                                    return [require('autoprefixer')];
                                }
                            }
                        }
                    },
                    {
                        loader: "sass-loader",
                        options: {
                            sourceMap: true,
                            sassOptions: {
                                outputStyle: "compressed",
                            },
                        },
                    },
                ],
            },
        ],
    },
    optimization: {
        minimize: true,
        minimizer: [
            new CssMinimizerPlugin(),
        ],
    },
};
