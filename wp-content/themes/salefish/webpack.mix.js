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
  .sass("assets/scss/pages/home.scss", "dest/pages")
  .js("assets/js/app.js", "dest")
  // Page-specific JS — only loaded on pages that need them (see functions.php)
  .js("assets/js/pages/home.js", "dest/pages")
  .js("assets/js/pages/blog.js", "dest/pages")
  .js("assets/js/pages/contact_us.js", "dest/pages")
  .js("assets/js/pages/content.js", "dest/pages")
  .js("assets/js/pages/single_post.js", "dest/pages")
  .options({
    postCss: [
      require("autoprefixer")({
        Browserslist: ["last 40 versions"],
      }),
    ],
  })
  .browserSync({
    proxy: "salefish.local",
    files: [`./*.php`, `./**/*.php`, `./dest/*.js`, `./dest/*.css`, `./dest/pages/*.js`, `./dest/pages/*.css`],
    injectCss: true,
    watch: true,
  });
