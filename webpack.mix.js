const mix = require('laravel-mix');

mix
    .js("resources/ts/entryPoints/overview.tsx", "dist/js")
    .js("resources/ts/entryPoints/detail.tsx", "dist/js")
    .sass('resources/sass/app.scss', 'dist/css')
    .webpackConfig({
        module: {
            rules: [
                {
                    test: /\.tsx?$/,
                    loader: "ts-loader",
                    exclude: /node_modules/,
                },
                {
                    test: /\.svg$/,
                    loader: 'raw-loader',
                },
            ],
        },
        resolve: {
            extensions: ["*", ".js", ".jsx", ".ts", ".tsx"],
        },
    })
