import dropdown from "../tools/dropdown";
import transition from "../tools/transition";

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

  const getUrlParameter = function (sParam) {
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
      if (anchor) anchor.scrollIntoView({ behavior: "smooth" });
    }

    // Button-based filter (replaces Semantic UI dropdown + Isotope)
    $(".filter-tab").on("click", function () {
      const selected = $(this).data("filter");

      $(".filter-tab").removeClass("active");
      $(this).addClass("active");

      if (selected === "all") {
        $(".items .item").show();
      } else {
        $(".items .item").hide();
        $('.items .item[data-category="' + selected + '"]').show();
      }
    });

    // Apply URL filter parameter on page load
    if (filter && filter !== "all") {
      const $btn = $(".filter-tab[data-filter='" + filter + "']");
      if ($btn.length) $btn.trigger("click");
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
            let { category, link, thumb, title, date } = post;
            const cat_slug = category[0].category_nicename;
            const cat_name = category[0].name;
            const pub_date = date || "";
            const is_video = cat_slug === "videos";
            const href = is_video ? link : link;

            $(".blog_articles").append(`
              <a href="${href}" class="item ${cat_slug} all" data-category="${cat_slug}"${is_video ? ' data-fancybox' : ''}>
                ${thumb ? `<div class="img_container">${thumb}</div>` : ""}
                <div class="item-body">
                  <span class="cat-badge ${cat_slug}">${cat_name}</span>
                  ${pub_date ? `<span class="post-date">Published: ${pub_date}</span>` : ""}
                  <h3 class="post-title">${title}</h3>
                  <span class="read-more">${is_video ? "Watch Video" : "Read More"}</span>
                </div>
              </a>
            `);
          });

          // Re-apply active filter to newly appended items
          const activeFilter = $(".filter-tab.active").data("filter");
          if (activeFilter && activeFilter !== "all") {
            $('.items .item[data-category!="' + activeFilter + '"]').hide();
          }

          // Re-init fancybox for any newly added video items
          $("[data-fancybox]").fancybox({
            buttons: ["close"],
            wheel: false,
            transitionEffect: "slide",
            toolbar: false,
            arrows: true,
            clickContent: false,
          });
        },
      });
    });
  }
});
