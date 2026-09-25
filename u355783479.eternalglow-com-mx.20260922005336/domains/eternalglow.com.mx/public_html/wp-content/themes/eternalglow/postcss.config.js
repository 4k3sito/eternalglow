const path = require('path');

module.exports = {
    plugins: [
        require('postcss-pxtorem')({
            rootValue: 16,
            propList: ['*'],
            mediaQuery: false,
            exclude: /node_modules/i
        }),
        // The utility maps in _utilities.scss generate ~4 MB of CSS; keep only the classes that are used.
        // WPBakery content lives in the database, so its classes come from purge-tokens.txt
        // (regenerate with `npm run collect-tokens` after adding new classes in the editor).
        require('@fullhuman/postcss-purgecss')({
            content: [
                path.join(__dirname, '**/*.php'),
                path.join(__dirname, 'assets/ts/**/*.ts'),
                path.join(__dirname, 'purge-tokens.txt'),
                // Classes these libraries add at runtime (show, collapsing, offcanvas-backdrop, wpcf7 states...).
                path.join(__dirname, 'node_modules/bootstrap/dist/js/bootstrap.js'),
                path.join(__dirname, 'node_modules/@fancyapps/ui/dist/fancybox/fancybox.umd.js'),
                path.join(__dirname, '../../plugins/contact-form-7/includes/js/index.js'),
            ],
            skippedContentGlobs: [path.join(__dirname, 'node_modules/**/*.php')],
            // Built with template strings, so the extractor cannot see them.
            safelist: {
                standard: [/^text-bg-/],
                greedy: [/^f-/, /^fancybox/, /^is-/, /^has-/, /^wpcf7/],
            },
        }),
        require('autoprefixer')(),
    ],
};
