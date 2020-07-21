window.onload = function(){
	var app = {
		init: function(){
		}
	}

	app.init()

	document.querySelector('#loader-fragment').style.opacity = '0';

	$('#open-filter').on('click',function(e){
		e.preventDefault()
		$('#filter').slideToggle()
	})

	$('#filter form .side .checkboxes .item .checkbox input').on('click',function(){
		$(this).parent().toggleClass('active')
	})

	document.body.classList.add('loaded')

	var mySwiper = new Swiper ('.swiper-container.projects-slider', {
		loop: false,
		slidesPerView: 4,
		spaceBetween: 30
	})

	var mySwipere = new Swiper ('.swiper-container.news-slider', {
		loop: false,
		slidesPerView: 3,
		spaceBetween: 30
	})

	var mySwiperee = new Swiper ('.gallery-container .swiper-container', {
		loop: false,
		slidesPerView: 4,
		spaceBetween: 30,
		breakpoints: {
			320: {
				slidesPerView: 1
			},
			768: {
				slidesPerView: 2
			},
			1024: {
				slidesPerView: 3
			},
			1366: {
				slidesPerView: 4
			}
		}
	})


	if (document.querySelector('#lightgallery') != undefined) {
		$('#lightgallery').lightGallery({
			selector: '.lg',
			thumbnail: true,
		});
	}

	$('.question-box .question').on('click',function(){
		if ($(this).parent().hasClass('active')) {
			$(this).parent().find('.answer').slideUp(100)
			$(this).parent().removeClass('active')
			return
		}
		$('.question-box').removeClass('active')
		$('.question-box .answer').slideUp(100)
		$(this).parent().find('.answer').slideDown(100)
		$(this).parent().addClass('active')
	})

	function cT(){
		var options = {
			useEasing: true,
			useGrouping: true,
			separator: ",",
			decimal: "."
		};

		var statistics = $("#statistic-section .container .box h2");

		statistics.each(function(index) {

			var value = $(statistics[index]).html();

			var statisticAnimation = new CountUp(statistics[index], 0, value, 0, 5, options);
			statisticAnimation.start();
		});
	}
	function check(){
		if ($('#statistic-section .container').hasClass('active-kfn')) {
			cT();
			return
		}
		requestAnimationFrame(check)
	}
	check();

	var observer = new IntersectionObserver((entries, observer) => {
		entries.forEach(entry => {
			if (entry.isIntersecting) {
				entry.target.classList.add('active-kfn')
				if (entry.target.classList.contains('chamberSliderContainer')) {
					entry.target.classList.add('active')
				}
			}
		});
	}, { rootMargin: "100px 0px 0px 0px" });
	document.querySelectorAll('.kfn_anim').forEach(img => { observer.observe(img) });

	window.addEventListener('scroll',function(){
		if (window.pageOffsetY > 0 || document.documentElement.scrollTop > 0) {
			document.body.classList.add('scrolled')
		}else {
			document.body.classList.remove('scrolled')
		}
	})
	if (window.pageOffsetY > 0 || document.documentElement.scrollTop > 0) {
		document.body.classList.add('scrolled')
	}else {
		document.body.classList.remove('scrolled')
	}

	function services(){
		var a = document.querySelectorAll('#services-section .container .box');
		a.forEach(function(elem){
			elem.addEventListener('click',function(){
				document.querySelector('#services-section').classList.remove('active')
				document.querySelector('#services-section').classList.add('activee')
				setTimeout(function(){
					document.querySelector('#services-section').classList.add('active')
				},100)
			})
		})
	}
	services();
	function detectActiveSection(){
		var a = document.querySelectorAll('.h-container'),
			b = document.querySelectorAll('header .container nav ul li a');

		for(var i = 0; i < b.length; i++) {
			if (a[i].getBoundingClientRect().top - window.innerHeight < 0 && a[i].getBoundingClientRect().top >= 0) {
				b[i].classList.add('active')
			}else {
				b[i].classList.remove('active')
			}
		}
	}
	detectActiveSection()

	window.addEventListener('scroll',function(){
		detectActiveSection()
	})

}