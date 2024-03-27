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
  if (
    document.querySelector(".images").lastChild.firstChild !== null &&
    document.querySelector(".images").lastChild.firstChild.firstChild.lastChild.value === ""
  ) {
    return;
  }

  const collectionHolder = document.querySelector("." + e.currentTarget.dataset.collectionHolderClass);

  const item = document.createElement("li");
  item.classList.add("tricks_form_images_" + collectionHolder.dataset.index);

  item.innerHTML = collectionHolder.dataset.prototype.replace(/__name__/g, collectionHolder.dataset.index);

  collectionHolder.appendChild(item);

  // Upload image preview inside div.image-preview
  item.querySelector("input[type=file]").addEventListener("change", function (e) {
    const file = e.currentTarget.files[0];
    const reader = new FileReader();
    const preview = document.querySelector(".image-preview");

    const div = document.createElement("div");
    const image = document.createElement("img");

    div.classList.add("preview-images", "tricks_form_images", "tricks_form_images_" + collectionHolder.dataset.index);

    div.dataset.index = collectionHolder.dataset.index;

    div.appendChild(image);

    reader.onloadend = function () {
      image.src = reader.result;
      preview.appendChild(div);
    };

    reader.readAsDataURL(file);

    // Au click sur la preview de l'image, on supprime l'élément

    div.addEventListener("click", deleteImageFormToCollection);

    collectionHolder.dataset.index++;
  });
}

// // // // // // //
// Ajout du collection form pour les vidéos
// // // // // // //

document.querySelectorAll(".add_video_item").forEach((btn) => {
  btn.addEventListener("click", addVideoFormToCollection);
});

function addVideoFormToCollection(e) {
  if (
    document.querySelector(".videos").lastChild.firstChild !== null &&
    document.querySelector(".videos").lastChild.firstChild.firstChild.lastChild.value === ""
  ) {
    return;
  }

  const collectionHolder = document.querySelector("." + e.currentTarget.dataset.collectionHolderClass);

  const item = document.createElement("li");
  item.classList.add("tricks_form_videos_" + collectionHolder.dataset.index + "_url");

  item.innerHTML = collectionHolder.dataset.prototype.replace(/__name__/g, collectionHolder.dataset.index);

  collectionHolder.appendChild(item);

  // On récupère la value de l'input et on l'injecte dans l'iframe si elle n'est pas déjà une iframe
  item.querySelector("input[type=text]").addEventListener("change", function (e) {
    const input = e.currentTarget;
    const div = document.createElement("div");
    const videoPreview = document.querySelector(".video-preview");

    if (input.value.includes("iframe")) {
      div.innerHTML = input.value;
      div.firstChild.classList.add("w-full", "h-full");
    } else {
      const iframe = document.createElement("iframe");
      iframe.src = input.value;
      iframe.classList.add("w-full", "h-full");
      div.appendChild(iframe);
    }

    div.classList.add("tricks_form_videos_" + collectionHolder.dataset.index, "tricks_form_videos", "preview-videos");

    div.dataset.index = collectionHolder.dataset.index;

    videoPreview.appendChild(div);

    // Au click sur la preview de la vidéo, on supprime l'élément

    div.addEventListener("click", deleteVideoFormToCollection);

    collectionHolder.dataset.index++;
  });
}

// // // // // // //
// Au click sur la preview de l'image ou de la vidéo, on supprime l'élément
// // // // // // //

function deleteImageFormToCollection(e) {
  let index = e.target.dataset.index;

  let items = document.querySelectorAll(".tricks_form_images_" + index);

  items.forEach((item) => {
    item.remove();
  });
}

function deleteVideoFormToCollection(e) {
  let index = e.target.dataset.index;

  let items = document.querySelectorAll(".tricks_form_videos_" + index, "#tricks_form_videos_" + index);

  console.log(items);

  items.forEach((item) => {
    item.remove();
  });
}

// // // // // // //
// Au click sur la preview de l'image ou de la vidéo, on supprime l'élément sur le formulaire d'édition
// // // // // // //

document.querySelectorAll(".tricks_form_images_edit").forEach((btn) => {
  btn.addEventListener("click", deleteImageFormToCollection);
});

document.querySelectorAll(".tricks_form_videos_edit").forEach((btn) => {
  btn.addEventListener("click", deleteVideoFormToCollection);
});
