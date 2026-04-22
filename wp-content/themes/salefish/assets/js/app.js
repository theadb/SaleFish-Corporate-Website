import { general } from "./general";
import { home } from "./pages/home";
import { content } from "./pages/content";
import { contact_us } from "./pages/contact_us";
import { blog } from "./pages/blog";
import { single_post } from "./pages/single_post";
import AOS from "aos";

window.addEventListener("load", function (event) {
  setTimeout(() => {
    $(".loading").addClass("active");
    $("footer").css("display", "block");
    AOS.init({
      duration: 350,
      offset: 0,
      once: true,
      easing: "ease-out",
      // Disable scroll animations on mobile — elements appear immediately,
      // preventing the "scroll too far before content loads" effect
      disable: function () { return window.innerWidth < 768; },
    });
  }, 150);
});
