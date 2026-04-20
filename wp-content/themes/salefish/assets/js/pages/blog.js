import isotope from "isotope-layout/dist/isotope.pkgd";

$(function () {
  let scroll = new SmoothScroll();
  let page = $("main").attr("class");

  $("[data-fancybox]").fancybox({
    // Options will go here
    buttons: ["close"],
    wheel: false,
    transitionEffect: "slide",
    toolbar: false,
    arrows: true,
    clickContent: false,
  });

  const getUrlParameter = function getUrlParameter(sParam) {
    var sPageURL = window.location.search.substring(1),
      sURLVariables = sPageURL.split("&"),
      sParameterName,
      i;

    for (i = 0; i < sURLVariables.length; i++) {
      sParameterName = sURLVariables[i].split("=");

      if (sParameterName[0] === sParam) {
        return sParameterName[1] === undefined
          ? true
          : decodeURIComponent(sParameterName[1]);
      }
    }
    return false;
  };
  if (page === "blog") {
    $("header .salefish_logo").attr(
      "src",
      BASEURL + "/img/dark_salefish_logo.png"
    );
    $(".down_arrow").attr("src", BASEURL + "/img/purple_down_arrow.svg");

    let filter = getUrlParameter("filter");

    if (filter) {
      let anchor = document.querySelector("#articles");
      scroll.animateScroll(anchor, {
        updateURL: false,
      });
    }

    window.$grid = $(".blog_articles").isotope({
      itemSelector: ".item",
    });
    window.$grid.isotope();

    setTimeout(() => {
      window.$grid.isotope();
    }, 3000);

    $("#blog-filter").on("change", function () {
      let value = $(this).val();
      $grid.isotope({ filter: value === "all" ? "*" : `.${value}` });
    });

    if (filter && filter !== "all") {
      $("#blog-filter").val(filter);
      $grid.isotope({ filter: `.${filter}` });
    }

    $(window).on("scroll", function () {
      if ($(window).scrollTop() > 1) {
        $("header .salefish_logo").attr(
          "src",
          BASEURL + "/img/salefish_logo.png"
        );
        $(".down_arrow").attr("src", BASEURL + "/img/down_arrow.svg");
      } else {
        $(".salefish_logo").attr(
          "src",
          BASEURL + "/img/dark_salefish_logo.png"
        );
        $(".down_arrow").attr("src", BASEURL + "/img/purple_down_arrow.svg");
      }
    });

    let currentPage = 1;

    $("#load-more").on("click", function () {
      currentPage++;
      $.ajax({
        type: "POST",
        url: ajaxurl,
        dataType: "json",
        data: {
          action: "load_more_post",
          paged: currentPage,
          nonce: salefishAjax.loadMoreNonce,
        },
        success: function (res) {
          if (currentPage >= res.max) {
            $("#load-more").fadeOut();
          }

          res.posts.forEach((post) => {
            let {
              category: category,
              link: link,
              thumb: thumb,
              title: title,
            } = post;

            $(".article_items .items").append(`
            <a href="${link}">
            	<div class="item blog all" style="position: absolute; left: 45px; top: 50px;">
            		<div>
            			<h3 class="${category[0].category_nicename}">
            				${category[0].name}
									</h3>
            			<p>
										${title}
									</p>
            		</div>
								${thumb}
								<span class="button ${category[0].category_nicename}">READ MORE</span>
            	</div>
            </a>
            `);
          });

          $grid.isotope("reloadItems").isotope();
        },
      });
    });
  }
});

export const blog = {};
