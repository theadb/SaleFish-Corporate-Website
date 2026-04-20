import isotope from "isotope-layout/dist/isotope.pkgd";

$(function () {
  let page = $("main").attr("class");

  $("[data-fancybox]").fancybox({
    buttons: ["close"],
    wheel: false,
    transitionEffect: "slide",
    toolbar: false,
    arrows: true,
    clickContent: false,
  });
});

export const blog = {};
