@php
    $mainSliderImages = DB::table('setting_gallery')->where('type',1)->get();
@endphp
<div class="kes-hero-banner desktop-banner hero-banner" data-content-type="row" data-appearance="full-bleed" data-enable-parallax="0" data-parallax-speed="0.5" data-background-images="{}" data-background-type="image" data-video-loop="true" data-video-play-only-visible="true" data-video-lazy-load="true" data-video-fallback-src data-element="main" data-pb-style="UO11EA1">
	<div data-content-type="html" data-appearance="default" data-element="main" data-decoded="true">
	<style>
		.hero-banner .main_slide img {
		height: auto;
		width: 100%;
		object-fit: contain;
		margin: 0 auto
		}
		@media only screen and (max-width: 767px) {
		.hero-banner .main_slide img {
		height:auto
		}
		}
		.hero-banner .pagebuilder-slide-wrapper {
		min-height: unset !important;
		background-size: cover !important
		}
		@media only screen and (max-width: 767px) {
		.hero-banner .pagebuilder-slide-wrapper {
		background-size:cover !important
		}
		}
		.hero-banner .pagebuilder-slide-wrapper .pagebuilder-overlay.pagebuilder-poster-overlay {
		min-height:660px !important;
		max-height: 660px !important
		}
		@media only screen and (max-width: 1799px) {
		.hero-banner .pagebuilder-slide-wrapper .pagebuilder-overlay.pagebuilder-poster-overlay {
		min-height:600px !important;
		max-height: 600px !important
		}
		}
		@media only screen and (max-width: 1499px) {
		.hero-banner .pagebuilder-slide-wrapper .pagebuilder-overlay.pagebuilder-poster-overlay {
		min-height:500px !important;
		max-height: 500px !important
		}
		}
		@media only screen and (max-width: 1199px) {
		.hero-banner .pagebuilder-slide-wrapper .pagebuilder-overlay.pagebuilder-poster-overlay {
		min-height:350px !important;
		max-height: 350px !important
		}
		}
		@media only screen and (max-width: 991px) {
		.hero-banner .pagebuilder-slide-wrapper .pagebuilder-overlay.pagebuilder-poster-overlay {
		min-height:300px !important;
		max-height: 300px !important
		}
		}
		@media only screen and (max-width: 768px) {
		.hero-banner .pagebuilder-slide-wrapper .pagebuilder-overlay.pagebuilder-poster-overlay {
		padding-top:25vh;
		min-height: 350px !important;
		max-height: 350px !important
		}
		}
		@media only screen and (max-width: 479px) {
		.hero-banner .pagebuilder-slide-wrapper .pagebuilder-overlay.pagebuilder-poster-overlay {
		min-height:300px !important;
		max-height: 300px !important
		}
		}
	</style>
	</div>
	<div class="pagebuilder-slider" data-content-type="slider" data-appearance="default" data-autoplay="true" data-autoplay-speed="4000" data-fade="false" data-infinite-loop="true" data-show-arrows="false" data-show-dots="true" data-element="main" data-pb-style="EBMLS9I">
	@foreach($mainSliderImages as $mainImage)
		<div data-content-type="slide" data-slide-name data-appearance="poster" data-show-button="never" data-show-overlay="never" data-element="main">
			<a href="javascript:void(0)" target data-link-type="category" title data-element="link">
				<div class="pagebuilder-slide-wrapper background-image-<?= $mainImage->id ?>" data-background-images="{{ asset($mainImage->images) }}" data-background-type="image" data-video-loop="true" data-video-play-only-visible="true" data-video-lazy-load="true" data-video-fallback-src data-element="wrapper" data-pb-style="PVAGAN2">
					<div class="pagebuilder-overlay pagebuilder-poster-overlay" data-overlay-color aria-label title data-element="overlay" data-pb-style="NJ5JGOL">
					<div class="pagebuilder-poster-content">
						<div data-element="content"></div>
					</div>
					</div>
				</div>
				<style type="text/css">.background-image-<?= $mainImage->id ?> {background-image: url({{ asset($mainImage->images) }});}</style>
			</a>
		</div>
		@endforeach
	</div>
</div>

<script>
	'use strict';
	
	(() => {
		const initCarousels = (elements) => {
			if (!Glider) {
				return;
			}
	
			const initProductCarousel = (carousel) => {
				const gliderElement = carousel.querySelector('[data-role="glider-content"]');
	
				if (!gliderElement) {
					return;
				}
	
				const gliderDots = carousel.querySelector('.glider-dots');
				const gliderPrev = carousel.querySelector('.glider-prev');
				const gliderNext = carousel.querySelector('.glider-next');
	
				const glider = new Glider(gliderElement, {
					slidesToShow: 2,
					slidesToScroll: 2,
					scrollLock: true,
					draggable: true,
					dragVelocity: 2.5,
					dots: gliderDots,
					arrows: {
						prev: gliderPrev,
						next: gliderNext,
					},
					responsive: [
						{
							breakpoint: 768,
							settings: {
								slidesToShow: 3,
								slidesToScroll: 3,
							}
						},
						{
							breakpoint: 1024,
							settings: {
								slidesToShow: 4,
								slidesToScroll: 4,
							}
						},
					],
				});
	
				carousel.classList.remove('overflow-x-scroll');
				gliderPrev.classList.remove('hidden');
				gliderNext.classList.remove('hidden');
	
				if (carousel.dataset.autoplay !== 'false') {
					gliderAutoplay(
						glider,
						carousel.dataset.autoplaySpeed,
						carousel.dataset.infiniteLoop
					);
				}
				window.addEventListener('page-builder-tab-activate', event => {
					const tab = event.detail.tab;
					if (tab && tab.contains(gliderElement)) {
						event.detail.nextTick.then(() => {
							requestAnimationFrame(() => glider.refresh(true))
						});
					}
				});
			};
	
			const initSliderCarousel = (slider) => {
				slider.innerHTML = `<div data-role="glider-content">${slider.innerHTML}</div>`;
				slider.classList.add('glider-contain');
	
				slider.insertAdjacentHTML(
					'beforeend',
					'\u000A\u003Cdiv\u0020class\u003D\u0022carousel\u002Dnav\u0020flex\u0020items\u002Dcenter\u0020justify\u002Dcenter\u0020flex\u002D1\u0020p\u002D4\u0022\u003E\u000A\u0020\u0020\u0020\u0020\u003Cbutton\u000A\u0020\u0020\u0020\u0020\u0020\u0020\u0020\u0020aria\u002Dlabel\u003D\u0022Previous\u0022\u000A\u0020\u0020\u0020\u0020\u0020\u0020\u0020\u0020class\u003D\u0022glider\u002Dprev\u0020w\u002D8\u0020h\u002D8\u0020mr\u002D1\u0020text\u002Dblack\u0020rounded\u002Dfull\u0020outline\u002Dnone\u0020focus\u003Aoutline\u002Dnone\u0020hidden\u0022\u003E\u000A\u0020\u0020\u0020\u0020\u0020\u0020\u0020\u0020\u003Csvg\u0020xmlns\u003D\u0022http\u003A\u002F\u002Fwww.w3.org\u002F2000\u002Fsvg\u0022\u0020fill\u003D\u0022none\u0022\u0020viewBox\u003D\u00220\u00200\u002024\u002024\u0022\u0020stroke\u002Dwidth\u003D\u00222\u0022\u0020stroke\u003D\u0022currentColor\u0022\u0020width\u003D\u002224\u0022\u0020height\u003D\u002224\u0022\u0020role\u003D\u0022img\u0022\u003E\u000A\u0020\u0020\u003Cpath\u0020stroke\u002Dlinecap\u003D\u0022round\u0022\u0020stroke\u002Dlinejoin\u003D\u0022round\u0022\u0020d\u003D\u0022M15\u002019l\u002D7\u002D7\u00207\u002D7\u0022\u002F\u003E\u000A\u003Ctitle\u003Echevron\u002Dleft\u003C\u002Ftitle\u003E\u003C\u002Fsvg\u003E\u000A\u0020\u0020\u0020\u0020\u003C\u002Fbutton\u003E\u000A\u0020\u0020\u0020\u0020\u003Cdiv\u0020role\u003D\u0022tablist\u0022\u0020class\u003D\u0022glider\u002Ddots\u0020select\u002Dnone\u0020flex\u0020flex\u002Dwrap\u0020mx\u002D1\u0020justify\u002Dcenter\u0020p\u002D0\u0020focus\u003Aoutline\u002Dnone\u0022\u003E\u003C\u002Fdiv\u003E\u000A\u0020\u0020\u0020\u0020\u003Cbutton\u000A\u0020\u0020\u0020\u0020\u0020\u0020\u0020\u0020aria\u002Dlabel\u003D\u0022Next\u0022\u000A\u0020\u0020\u0020\u0020\u0020\u0020\u0020\u0020class\u003D\u0022glider\u002Dnext\u0020w\u002D8\u0020h\u002D8\u0020ml\u002D1\u0020text\u002Dblack\u0020rounded\u002Dfull\u0020outline\u002Dnone\u0020focus\u003Aoutline\u002Dnone\u0020hidden\u0022\u003E\u000A\u0020\u0020\u0020\u0020\u0020\u0020\u0020\u0020\u003Csvg\u0020xmlns\u003D\u0022http\u003A\u002F\u002Fwww.w3.org\u002F2000\u002Fsvg\u0022\u0020fill\u003D\u0022none\u0022\u0020viewBox\u003D\u00220\u00200\u002024\u002024\u0022\u0020stroke\u002Dwidth\u003D\u00222\u0022\u0020stroke\u003D\u0022currentColor\u0022\u0020width\u003D\u002224\u0022\u0020height\u003D\u002224\u0022\u0020role\u003D\u0022img\u0022\u003E\u000A\u0020\u0020\u003Cpath\u0020stroke\u002Dlinecap\u003D\u0022round\u0022\u0020stroke\u002Dlinejoin\u003D\u0022round\u0022\u0020d\u003D\u0022M9\u00205l7\u00207\u002D7\u00207\u0022\u002F\u003E\u000A\u003Ctitle\u003Echevron\u002Dright\u003C\u002Ftitle\u003E\u003C\u002Fsvg\u003E\u000A\u0020\u0020\u0020\u0020\u003C\u002Fbutton\u003E\u000A\u003C\u002Fdiv\u003E\u000A'
				);
	
				const gliderElement = slider.querySelector('[data-role="glider-content"]');
				const gliderDots = slider.querySelector('.glider-dots');
				const gliderPrev = slider.querySelector('.glider-prev');
				const gliderNext = slider.querySelector('.glider-next');
	
				const glider = new Glider(gliderElement, {
					slidesToShow: 1,
					slidesToScroll: 1,
					scrollLock: true,
					scrollLockDelay: 250,
					draggable: true,
					dragVelocity: 2.5,
					dots: gliderDots,
					arrows: {
						prev: gliderPrev,
						next: gliderNext,
					},
				});
	
				slider.classList.add('glider-initialized');
				if (slider.dataset.showArrows === 'true') {
					gliderPrev.classList.remove('hidden');
					gliderNext.classList.remove('hidden');
				}
	
				if (slider.dataset.autoplay !== 'false') {
					gliderAutoplay(
						glider,
						slider.dataset.autoplaySpeed,
						slider.dataset.infiniteLoop
					);
				}
			};
	
			const gliderAutoplay = (glider, milliseconds, loop) => {
				const pagesCount = glider.track.childElementCount;
				let slideTimeout = null;
				let nextIndex = 1;
				let paused = false;
	
				const slide = () => {
					slideTimeout = setTimeout(
						() => {
							if (loop && nextIndex >= pagesCount) {
								nextIndex = 0;
							}
							glider.scrollItem(nextIndex);
						},
						parseInt(milliseconds)
					);
				};
	
				glider.ele.addEventListener('glider-animated', () => {
					nextIndex = glider.slide + glider.opt.slidesToScroll;
					window.clearInterval(slideTimeout);
					if (!paused && (loop || nextIndex < pagesCount)) {
						slide();
					}
				});
	
				const pause = () => {
					if (!paused) {
						clearInterval(slideTimeout);
						paused = true;
					}
				};
	
				const unpause = () => {
					if (paused) {
						slide();
						paused = false;
					}
				};
	
				glider.ele.parentElement.addEventListener('mouseover', pause, {passive: true});
				glider.ele.parentElement.addEventListener('touchstart', pause, {passive: true});
				glider.ele.parentElement.addEventListener('mouseout', unpause, {passive: true});
				glider.ele.parentElement.addEventListener('touchend', unpause, {passive: true});
	
				slide();
			};
	
			elements.forEach(element => {
				if (element.dataset.contentType === 'products') {
					initProductCarousel(element);
				}
				if (element.dataset.contentType === 'slider') {
					initSliderCarousel(element);
				}
			});
		};
	
		window.addEventListener('DOMContentLoaded', () => {
			const carouselElements = document.querySelectorAll(
				`[data-content-type="products"][data-appearance="carousel"],
				[data-content-type="slider"]`
			);
	
			if (carouselElements.length > 0) {
				const script = document.createElement('script');
				script.type = 'text/javascript';
	
				script.addEventListener('load', () => {
					initCarousels(carouselElements);
				});
	
				script.src = 'https\u003A\u002F\u002Fwww.keslighting.co.uk\u002Fstatic\u002Fversion1719835716\u002Ffrontend\u002FKesLighting\u002Fupgrade\u002Fen_GB\u002FMagento_PageBuilder\u002Fjs\u002Fglider.min.js';
				document.head.appendChild(script);
			}
		});
	})();
</script>
