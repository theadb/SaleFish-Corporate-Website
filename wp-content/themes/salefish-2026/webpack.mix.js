const mix = require("laravel-mix");

mix
  .sass("assets/scss/app.scss", "dest")
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
