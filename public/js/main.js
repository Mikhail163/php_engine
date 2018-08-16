"use strict";

document.addEventListener("DOMContentLoaded", ready);

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


function ready() {
	calcLoad();
}

let a_in_focus = false;
let b_in_focus = false;
let a = null;
let b = null;
let operator = null;
let result = null;
function calcLoad() {
	
	let body = document.getElementById("calc");
	
	if (body != null) {
		
		a = document.getElementById("calc_a");
		b = document.getElementById("calc_b");
		operator = document.getElementById("calc_operation");
		
		result = document.getElementById("calc_result");
		
		a.addEventListener('focus', () => {a_in_focus = true; b_in_focus = false});
		a.addEventListener('keyup', () => changeNumber());
		a.addEventListener('blur', () => {a_in_focus = false});
		
		operator.addEventListener('change', () => calcResult());
		
		b.addEventListener('focus', () => {b_in_focus = true; a_in_focus = false});
		b.addEventListener('keyup', () => changeNumber());
		b.addEventListener('blur', () => {b_in_focus = false});
	}
	
}

function changeNumber () {
	
	let number;
	
	if (a_in_focus) {
		number = a;
	}
	else if (b_in_focus) {
		number = b;
	}
	else {
		return -1;
	}
	
	if (!isNumeric(number.value)) { // введено не число
	    // показать ошибку
		number.classList.add("number_error");
		number.classList.remove("number_ok");
	} else {
		//number.value = (number.value == "")?0:number.value;
	    number.classList.add("number_ok");
		number.classList.remove("number_error");
		
		calcResult();
	}
	
	return 0;
}

function calcResult() {
	
	if (!isNumeric(a.value) || !isNumeric(b.value)) {
		result.innerHTML = "Аргументы должны быть числами";	
		isOkVal(a);
		isOkVal(b);
		return -1;
	}
	
	a.value = (a.value == "")?0:a.value;
	b.value = (b.value == "")?0:b.value;
	
	let result_string;
	
	switch (operator.value) {
		case '+':
			result_string = +a.value + +b.value;
			break;
		case '-':
			result_string = a.value - b.value;
			break;
		case '*':
			result_string = a.value * b.value;
			break;
		case '/':
			if (b.value == 0) {
				result_string = "бесконечность (на ноль делить нельзя)";
				b.classList.add("number_error");
				b.classList.remove("number_ok");
			}
			else
				result_string = a.value / b.value;
			break;
	}
	
	result.innerHTML = " = " + result_string;	
	
}

function isOkVal(element) {
	if (!isNumeric(element.value)) { // введено не число
	    // показать ошибку
		element.classList.add("number_error");
		element.classList.remove("number_ok");
	} else {
		//number.value = (number.value == "")?0:number.value;
		element.classList.add("number_ok");
		element.classList.remove("number_error");
	}
}

function isNumeric(n) {
    
	   return !isNaN(parseFloat(n)) && isFinite(n);
	    
	   // Метод isNaN пытается преобразовать переданный параметр в число. 
	   // Если параметр не может быть преобразован, возвращает true, иначе возвращает false.
	   // isNaN("12") // false 
	}
