"use strict";

function openInModule(img_id) {
	
	closeImage();
	
	let body = document.body;
	
	let img = document.getElementById(img_id);
	
	let big_img = document.createElement('div');
	big_img.classList.add("modal_image");
	big_img.id = "modal_image_id";
	
	let close = document.createElement("div");
	close.classList.add("close");
	close.innerHTML = "Закрыть";	
	big_img.appendChild(close);
	
	close.addEventListener('click', () => closeImage());
	
	let div_for_image = document.createElement("div");
	div_for_image.classList.add("div_for_image");	
	big_img.appendChild(div_for_image);
	
	let picture = document.createElement("img");
	picture.setAttribute('src', img.src);
	div_for_image.appendChild(picture);
	
	
	body.classList.toggle("hidden");
	body.appendChild(big_img);
	
}

function closeImage() {
	let img = document.getElementById("modal_image_id");
	
	if (img != null) {
		img.remove();
		document.body.classList.toggle("hidden");
	}
	
}