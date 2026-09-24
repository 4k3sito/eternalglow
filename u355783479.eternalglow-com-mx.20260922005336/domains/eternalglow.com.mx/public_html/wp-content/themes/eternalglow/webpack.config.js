const webpack = require('webpack');
const path = require('path');
const del = require('del');
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const TerserPlugin = require("terser-webpack-plugin");
const { WebpackManifestPlugin } = require('webpack-manifest-plugin');

del(['dist/*']).then(paths => {
    console.log('Deleted files and folders:\n' + paths.join('\n'));
});

module.exports = env => {

	const mode = env.NODE_ENV;
	const hash = env.HASH ? `.[hash:${env.HASH}]` : '';

	return {
		mode,
		cache: {
			type: 'filesystem',
			allowCollectingMemory: true,
		},
		entry: {
			app: path.resolve(__dirname, 'assets/ts/app.ts')
		},
		output: {
			path: path.resolve(__dirname, 'dist'),
			publicPath: '',
			filename: `[name]${hash}.js`,
		},
		module: {
			rules: [
				{
				  	test: /\.tsx?$/,
				  	use: 'ts-loader',
				  	exclude: /node_modules/,
				},
				{
					test: /\.(sc|c)ss$/,
					use: [
						MiniCssExtractPlugin.loader,
						"css-loader",
						"postcss-loader",
						"sass-loader"
					]
				},
				{
					test: /\.(eot|svg|ttf|woff|woff2|jpg|png|gif)$/,
					type: 'asset/resource',
					generator: {
					  	emit: false
					}
				},
			]
		},
		resolve: {
			extensions: ['.ts', '.js'],
			alias: {
				Css: path.resolve(__dirname, 'assets/css'),
				Scss: path.resolve(__dirname, 'assets/scss'),
				Ts: path.resolve(__dirname, 'assets/ts'),
			}
		},
		plugins: [
			new webpack.ProvidePlugin({
				$: 'jquery',
				jQuery: 'jquery',
				'window.jQuery': 'jquery',
			}),
			new MiniCssExtractPlugin({
				filename: `[name]${hash}.css`,
				chunkFilename: `[id]${hash}.css`,
			}),
			new WebpackManifestPlugin({
				fileName: 'rev-manifest.json'
			}),
		],
		optimization: {
			minimize: true,
			minimizer: [new TerserPlugin({
			  	extractComments: false,
			})],
		},
	}
};