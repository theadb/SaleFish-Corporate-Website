const mix = require("laravel-mix");

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
mix
  .sass("assets/scss/app.scss", "dest")
  .js("assets/js/app.js", "dest")
  .options({
    postCss: [
      require("autoprefixer")({
        Browserslist: ["last 40 versions"],
      }),
    ],
  })
  .browserSync({
    proxy: "salefish.local",
    files: [`./*.php`, `./**/*.php`, `./dest/*.js`, `./dest/*.css`],
    injectCss: true,
    watch: true,
  });
