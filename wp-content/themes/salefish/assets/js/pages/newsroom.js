import dropdown from "../tools/dropdown";
import transition from "../tools/transition";
import isotope from "isotope-layout/dist/isotope.pkgd";

$(function () {
  let scroll = new SmoothScroll();
  let page = $("main").attr("class");

  $("[data-fancybox]").fancybox({
    // Options will go here
    buttons: ["close"],
    wheel: false,
    transitionEffect: "slide",
    // thumbs          : false,
    // hash            : false,
    // loop: true,
    // keyboard        : true,
    toolbar: false,
    // animationEffect : false,
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
  if (page === "newsroom") {
    $("header .salefish_logo").attr(
      "src",
      BASEURL + "/img/dark_salefish_logo.png"
    );
    $(".down_arrow").attr("src", BASEURL + "/img/purple_down_arrow.svg");

    let filter = getUrlParameter("filter");

    if (filter) {
      let anchor = document.querySelector("#articles");
      console.log("anchor: ", anchor);
      scroll.animateScroll(anchor, {
        updateURL: false,
      });
    }

    window.$grid = $(".newsroom_articles").isotope({
      itemSelector: ".item",
    });
    window.$grid.isotope();

    setTimeout(() => {
      console.log("run");
      window.$grid.isotope();
    }, 3000);

    $(".ui.dropdown").dropdown({
      onChange(value, text, $choice) {
        console.log("value: ", value);
        $grid.isotope({ filter: `.${value}` });
      },
    });

    // $("#watch_video").on("click", function () {
    //   console.log("fefe");
    //   $(".ui.dropdown").dropdown("set selected", "videos");
    // });

    switch (filter) {
      case "success-stories":
        $(".ui.dropdown").dropdown("set selected", "success-stories");
        break;
      case "press":
        $(".ui.dropdown").dropdown("set selected", "press");
        break;
      case "blog":
        $(".ui.dropdown").dropdown("set selected", "blog");
        break;
      case "videos":
        $(".ui.dropdown").dropdown("set selected", "videos");
        break;
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
        },
        success: function (res) {
          console.log("res: ", res);
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
