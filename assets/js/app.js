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

// // // // // // //
// Ajout du collection form pour les images
// // // // // // //

document.querySelectorAll(".add_image_item").forEach((btn) => {
  btn.addEventListener("click", addImageFormToCollection);
});

function addImageFormToCollection(e) {
  console.log(e);
  if (
    document.querySelector(".images").lastChild.firstChild !== null &&
    document.querySelector(".images").lastChild.firstChild.firstChild.lastChild.value === ""
  ) {
    return;
  }

  const collectionHolder = document.querySelector("." + e.currentTarget.dataset.collectionHolderClass);

  const item = document.createElement("li");

  item.innerHTML = collectionHolder.dataset.prototype.replace(/__name__/g, collectionHolder.dataset.index);

  collectionHolder.appendChild(item);

  // Upload image preview inside div.image-preview
  item.querySelector("input[type=file]").addEventListener("change", function (e) {
    const file = e.currentTarget.files[0];
    const reader = new FileReader();
    const preview = document.querySelector(".image-preview");

    const image = document.createElement("img");

    image.classList.add("w-1/5", "pr-2", "pb-2", "object-cover", "h-max");

    reader.onloadend = function () {
      image.src = reader.result;
      preview.appendChild(image);
    };

    reader.readAsDataURL(file);
  });

  collectionHolder.dataset.index++;
}

// // // // // // //
// Ajout du collection form pour les vidéos
// // // // // // //

document.querySelectorAll(".add_video_item").forEach((btn) => {
  btn.addEventListener("click", addVideoFormToCollection);
});

function addVideoFormToCollection(e) {
  console.log(e);
  if (
    document.querySelector(".videos").lastChild.firstChild !== null &&
    document.querySelector(".videos").lastChild.firstChild.firstChild.lastChild.value === ""
  ) {
    return;
  }

  const collectionHolder = document.querySelector("." + e.currentTarget.dataset.collectionHolderClass);

  const item = document.createElement("li");

  item.innerHTML = collectionHolder.dataset.prototype.replace(/__name__/g, collectionHolder.dataset.index);

  collectionHolder.appendChild(item);

  collectionHolder.dataset.index++;
}
