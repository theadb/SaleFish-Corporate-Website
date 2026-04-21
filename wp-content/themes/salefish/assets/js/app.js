import { general } from "./general";
import { home } from "./pages/home";
import { content } from "./pages/content";
import { contact_us } from "./pages/contact_us";
import { blog } from "./pages/blog";
import { single_post } from "./pages/single_post";
import AOS from "aos";

window.addEventListener("load", function (event) {
  console.log("window load");
  setTimeout(() => {
    $(".loading").addClass("active");
    $("footer").css("display", "block");
    AOS.init();
  }, 800);
});
