(function () {
	var pre = document.getElementsByClassName('pre-code'),
		pl = pre.length;
	for (var i = 0; i < pl; i++) {
		pre[i].innerHTML = '<span class="line-number"></span>' + pre[i].innerHTML;
		var num = pre[i].innerHTML.split(/\n/).length;
		for (var j = 0; j < num; j++) {
			var line_num = pre[i].getElementsByTagName('span')[0];
			line_num.innerHTML += '<span>' + (j + 1) + '</span>';
		}
	}
})();

jQuery(document).ready(function ($) {

	/* Show - Hide TOC */
	$(document).ready(function () { $(".check-table").on("click", function () { $("#index-table").toggle(200) }) });


	//SMOOTH SCROLL

	$('a[href*="#"]').not('[href="#"]').not('[href="#0"]').not('[href^="#tab"]').click(function (event) {

		if (
			location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') &&
			location.hostname == this.hostname
		) {

			var target = $(this.hash);
			target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');

			if (target.length) {

				event.preventDefault();
				$('html, body').animate({
					scrollTop: target.offset().top
				}, 1000, function () {

					var $target = $(target);
					$target.focus();
					if ($target.is(":focus")) {
						return false;
					} else {
						$target.attr('tabindex', '-1');
						$target.focus();
					};
				});
			}
		}
	});

});

(function () {
	if (document.body.classList.contains('woocommerce-cart') || document.body.classList.contains('woocommerce-checkout') || window.innerWidth < 768 || !document.getElementById('site-header-cart')) {
		return;
	}

	window.addEventListener('load', function () {
		var cart = document.querySelector('.site-header-cart');

		cart.addEventListener('mouseover', function () {
			var windowHeight = window.outerHeight,
				cartBottomPos = this.querySelector('.widget_shopping_cart_content').getBoundingClientRect().bottom + this.offsetHeight,
				cartList = this.querySelector('.cart_list');

			if (cartBottomPos > windowHeight) {
				cartList.style.maxHeight = '15em';
				cartList.style.overflowY = 'auto';
			}
		});
	});
})();