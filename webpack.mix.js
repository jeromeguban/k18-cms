const mix = require('laravel-mix');
const tailwindcss = require("tailwindcss");

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

// mix.js('resources/js/app.js', 'public/js')
//     .vue()
//     .sass('resources/sass/app.scss', 'public/css');

// mix.js('resources/js/admin.js', 'public/js').vue()
//     .js('resources/js/main.js', 'public/js').vue();


mix.options({
        terser: {
            terserOptions: {
                compress: {
                    warnings: false,
                    // drop_console: mix.inProduction() ? true : false, // remove console.log statements
                    // drop_debugger: mix.inProduction() ? true : false, // remove debugger statements
                    pure_funcs: mix.inProduction() ? ['alert'] : []
                }
            }
        }
    })
    .js("src/js/app.js", "dist/js")
    .js('resources/js/admin.js', 'public/js')
    .js('resources/js/main.js', 'public/js')
    // .js('resources/js/nav.js', 'public/js')
    .js('resources/js/live-auction.js', 'public/js')
    .sass("src/sass/app.scss", "dist/css")
    .options({
        processCssUrls: false,
        postCss: [tailwindcss("./tailwind.config.js")],
    })
    .autoload({
        "cash-dom": ["cash"],
    })
    .vue()

    .version();
