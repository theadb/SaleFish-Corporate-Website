$(function () {
  let page = $("main").attr("class");
  if (page === "terms" || page === "policy") {
    $("header .salefish_logo").attr(
      "src",
      BASEURL + "/img/dark_salefish_logo.png"
    );
    $(".down_arrow").attr("src", BASEURL + "/img/purple_down_arrow.svg");

    window.addEventListener('scroll', function () {
      if (window.scrollY > 1) {
        $("header .salefish_logo").attr("src", BASEURL + "/img/salefish_logo.png");
        $(".down_arrow").attr("src", BASEURL + "/img/down_arrow.svg");
      } else {
        $(".salefish_logo").attr("src", BASEURL + "/img/dark_salefish_logo.png");
        $(".down_arrow").attr("src", BASEURL + "/img/purple_down_arrow.svg");
      }
    }, { passive: true });
  }
});
