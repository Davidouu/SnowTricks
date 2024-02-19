/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import Alpine from "alpinejs";
import "../css/app.css";

window.Alpine = Alpine;

Alpine.start();
