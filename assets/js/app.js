/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import "../css/app.css";

$(document).ready(function () {
  setTimeout(function () {
    $(".flash-message").fadeOut("slow");
  }, 5000);
});
