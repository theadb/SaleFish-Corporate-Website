import $ from "jquery";
import parsley from "./tools/parsley";
import flowtype from "./tools/flowtype";
import mask from "./tools/jquery.mask";

$(function () {
  if ( new URLSearchParams( window.location.search ).get( 'salefish_verified' ) === '1' ) {
    $(".thank_you_msg").fadeIn();
    history.replaceState( null, '', window.location.pathname );
  }

  let page = $("main").attr("class");

  $.fn.isInViewport = function () {
    var elementTop = $(this).offset().top;
    var elementBottom = elementTop + $(this).outerHeight();

    var viewportTop = $(window).scrollTop();
    var viewportBottom = viewportTop + $(window).height();

    return elementBottom > viewportTop && elementTop < viewportBottom;
  };

  let pathname = $(location).attr("pathname");

  let scroll = new SmoothScroll('a[href*="#"]', {
    offset: function (anchor, toggle) {
      return 70;
    },
  });

  $("#phone").mask("000-000-0000");

  let activeMenu = pathname.startsWith("/de") ? ".floating_menu_de"
    : pathname.startsWith("/tr") ? ".floating_menu_tr"
    : ".floating_menu_en";

  $(".hamburger--emphatic").on("click", function () {
    $(this).toggleClass("is-active");
    $(activeMenu).fadeToggle();
  });
  $(".sales_login").on("click", function () {
    $(".sales_login_menu").fadeToggle();
  });
  $(".languages").on("click", function () {
    $(".languages .down_arrow").toggleClass("active");
    $(".languages_option").fadeToggle();
  });
  $(document).on("click", function (e) {
    if ($(e.target).closest(".languages").length === 0) {
      $(".languages_option").fadeOut();
      $(".languages .down_arrow").removeClass("active");
    }
    if ($(e.target).closest(".sales_login").length === 0) {
      $(".sales_login_menu").fadeOut();
    }
  });

  $(".privacy_policy_menu").on("click", function () {
    $(".privacy_policy").addClass("active");
    $(".hamburger--emphatic").toggleClass("is-active");
    $(activeMenu).fadeToggle();
    $("body").css("overflow", "hidden");
  });

  $(".close_privacy").on("click", function () {
    $(".privacy_policy").removeClass("active");
    $("body").css("overflow", "auto");
  });

  $(".terms_menu").on("click", function () {
    $(".terms_popup").addClass("active");
    $(".hamburger--emphatic").toggleClass("is-active");
    $(activeMenu).fadeToggle();
    $("body").css("overflow", "hidden");
  });

  $(".close_terms").on("click", function () {
    $(".terms_popup").removeClass("active");
    $("body").css("overflow", "auto");
  });

  $(".privacy_policy_menu_footer").on("click", function () {
    $(".privacy_policy").addClass("active");
    $("body").css("overflow", "hidden");
  });
  $(".terms_menu_footer").on("click", function () {
    $(".terms_popup").addClass("active");
    $("body").css("overflow", "hidden");
  });

  $(window).on("scroll", function () {
    var isMobile = $(window).width() <= 768;
    var menuTop  = isMobile ? "80px" : ($(window).scrollTop() > 1 ? "60px" : "70px");

    if ($(window).scrollTop() > 1) {
      $("header").addClass("active");
      $(".floating_menu").css("top", menuTop);
      $(".sales_login_menu").css("top", menuTop);
      $(".privacy_policy").css({
        top: "70px",
        height: "calc(100% - 70px)",
      });
      $(".terms_popup").css({
        top: "70px",
        height: "calc(100% - 70px)",
      });
    } else {
      $("header").removeClass("active");
      $(".floating_menu").css("top", menuTop);
      $(".sales_login_menu").css("top", menuTop);
      $(".privacy_policy").css({
        top: "100px",
        height: "calc(100% - 100px)",
      });
      $(".terms_popup").css({
        top: "100px",
        height: "calc(100% - 100px)",
      });
    }
  });

  $(".floating_menu .mobile").on("click", function () {
    $(".hamburger--emphatic").toggleClass("is-active");
    $(activeMenu).fadeToggle();
  });

  switch (pathname) {
    case "/au/":
      $(".flag_active").html(`
					<img class="flag" src="/wp-content/themes/salefish/img/flags/australia.png">
				`);
      $(".languages_option").html(`
					<ul>
						<li>
							<a href="/">
								<img class="flag" src="/wp-content/themes/salefish/img/flags/us.png">
								<img class="flag" src="/wp-content/themes/salefish/img/flags/canada.png">
							</a>
						</li>
						<li>
							<a href="/de">
								<img class="flag" src="/wp-content/themes/salefish/img/flags/germany.png">
							</a>
						</li>
						<li>
							<a href="/tr">
								<img class="flag" src="/wp-content/themes/salefish/img/flags/turkey.png">
							</a>
						</li>
					</ul>
				`);
      break;
    case "/tr/":
      $(".flag_active").html(`
					<img class="flag" src="/wp-content/themes/salefish/img/flags/turkey.png">
				`);
      $(".languages_option").html(`
					<ul>
						<li>
							<a href="/">
								<img class="flag" src="/wp-content/themes/salefish/img/flags/us.png">
								<img class="flag" src="/wp-content/themes/salefish/img/flags/canada.png">
							</a>
						</li>
						<li>
							<a href="/au">
								<img class="flag" src="/wp-content/themes/salefish/img/flags/australia.png">
							</a>
						</li>
						<li>
							<a href="/de">
								<img class="flag" src="/wp-content/themes/salefish/img/flags/germany.png">
							</a>
						</li>
					</ul>
				`);
      break;
    case "/de/":
      $(".flag_active").html(`
					<img class="flag" src="/wp-content/themes/salefish/img/flags/germany.png">
				`);
      $(".languages_option").html(`
					<ul>
						<li>
							<a href="/">
								<img class="flag" src="/wp-content/themes/salefish/img/flags/us.png">
								<img class="flag" src="/wp-content/themes/salefish/img/flags/canada.png">
							</a>
						</li>
						<li>
							<a href="/au">
								<img class="flag" src="/wp-content/themes/salefish/img/flags/australia.png">
							</a>
						</li>
						<li>
							<a href="/tr">
								<img class="flag" src="/wp-content/themes/salefish/img/flags/turkey.png">
							</a>
						</li>
					</ul>
				`);
      break;
    default:
      $(".flag_active").html(`
					<img class="flag" src="/wp-content/themes/salefish/img/flags/us.png">
					<img class="flag" src="/wp-content/themes/salefish/img/flags/canada.png">
				`);
      $(".languages_option").html(`
					<ul>
						<li>
							<a href="/au">
								<img class="flag" src="/wp-content/themes/salefish/img/flags/australia.png">
							</a>
						</li>
						<li>
							<a href="/de">
								<img class="flag" src="/wp-content/themes/salefish/img/flags/germany.png">
							</a>
						</li>
						<li>
							<a href="/tr">
								<img class="flag" src="/wp-content/themes/salefish/img/flags/turkey.png">
							</a>
						</li>
					</ul>
				`);
      break;
  }

  switch (pathname) {
    case "/our-story/":
      $(".our_story_nav a").addClass("active");
      break;
    case "/blog/":
    case "/blog":
      $(".blog_nav a").addClass("active");
      break;
    case "/contact-us/":
      $(".contact_us_nav a").addClass("active");
      break;
    case "/terms-of-use/":
      $(".terms_of_use_nav a").addClass("active");
      break;
    case "/privacy-policy/":
      $(".privacy_policy_nav a").addClass("active");
      break;
    case "/features/":
      $(".features_nav a").addClass("active");
      break;
    case "/partners/":
      $(".partners_nav a").addClass("active");
      break;
    case "/awards/":
      $(".awards_nav a").addClass("active");
      break;
  }

  // Features is a hash anchor on the homepage — highlight it on load if hash matches
  if (pathname === "/" && window.location.hash === "#features") {
    $(".features_nav a, .features_li a").addClass("active");
  }

  // On click: immediately mark Features as active and clear other nav highlights
  $(".features_nav a, .features_li a").on("click", function () {
    $("header nav > ul > li > a, header nav > ul > li > span").removeClass("active");
    $(this).addClass("active");
  });

  $("#reg_form").parsley();
  $("#agent_form").parsley();
  $("#partner_form").parsley();

  // REG FORM
  $("#reg_form").on("submit", function (e) {
    e.preventDefault();
    $.ajax({
      url: salefishAjax.ajaxurl,
      type: "POST",
      dataType: "json",
      data: $(this).serialize() + "&action=mailchimp_register&nonce=" + salefishAjax.nonce,
      success: function (res) {
        if (res.success) {
          $(".thank_you_msg").fadeIn();
        }
      },
    });
  });

  // AGENT FORM
  $("#agent_form").on("submit", function (e) {
    e.preventDefault();
    $.ajax({
      url: salefishAjax.ajaxurl,
      type: "POST",
      dataType: "json",
      data: $(this).serialize() + "&action=agents_register&nonce=" + salefishAjax.nonce,
      success: function (res) {
        if (res.success) {
          $(".thank_you_msg").fadeIn();
        }
      },
    });
  });

  // PARTNER FORM
  $("#partner_form").on("submit", function (e) {
    e.preventDefault();
    $.ajax({
      url: salefishAjax.ajaxurl,
      type: "POST",
      dataType: "json",
      data: $(this).serialize() + "&action=partner_register&nonce=" + salefishAjax.nonce,
      success: function (res) {
        if (res.success) {
          $(".thank_you_msg").fadeIn();
        }
      },
    });
  });

  // CLOSE THANK YOU MESSAGE
  $(".close_thank_you_msg").on("click", function () {
    $(".thank_you_msg").fadeOut();
  });

});
