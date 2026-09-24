module.exports = {
    plugins: [
        require('postcss-pxtorem')({
            rootValue: 16,
            propList: ['*'],
            mediaQuery: false,
            exclude: /node_modules/i
        }),
        require('autoprefixer')(),
    ],
};