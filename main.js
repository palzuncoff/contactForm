'use strict';

/* Mobile Menu Click
/* ## ## ## ## ## ## ## ## ## ## ## ## ## ## ## ## ## ## ## ## ## ## ## ## ## ## */

$('#menu_button').click(function(){
	$(this).toggleClass('clicked');
	$('.dropdown-menu').slideToggle();
});

/* Team MORE
/* ## ## ## ## ## ## ## ## ## ## ## ## ## ## ## ## ## ## ## ## ## ## ## ## ## ## */

function openMore(element) {
  element.classList.add('more_active');
  element.innerText = 'less';
  element.parentElement.nextElementSibling.classList.add('more-active');
  element.closest('.team__item').classList.add('more-active');
  element.closest('.team__content').classList.add('opacity');
}

function closeMore(element) {
  element.classList.remove('more_active');
  element.innerText = 'more';
  element.parentElement.nextElementSibling.classList.remove('more-active');
  element.closest('.team__item').classList.remove('more-active');
  element.closest('.team__content').classList.remove('opacity');
}

function closeAllMore() {
  var allMore = document.querySelectorAll('.team__more');
  allMore.forEach(closeMore);
}

$('.team__more').on('click',function(e) {
  if (e.target.classList.contains('more_active')) {
    closeAllMore();
  } else {
    closeAllMore();
    openMore(e.target);
  }
});

const allMore = document.querySelectorAll('.team__more');

/* Enroll in Courses checkbox
/* ## ## ## ## ## ## ## ## ## ## ## ## ## ## ## ## ## ## ## ## ## ## ## ## ## ## */

$(".enroll__checkbox input").on("click", function(){
  $(this).parent().toggleClass("isSelected");
});
