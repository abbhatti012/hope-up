document.addEventListener("DOMContentLoaded", function () {
    const prevButton = document.querySelector('.kcResultItemNavigationButtonLeft');
    const nextButton = document.querySelector('.kcResultItemNavigationButtonRight');
    const sliderContainer = document.querySelector('.kcResultsInner');
    const slides = document.querySelectorAll('.kcResultItem');
    let currentIndex = 0;
    const slidesToShow = 4;

    function updateSlider() {
        const containerWidth = sliderContainer.offsetWidth;
        const itemWidth = containerWidth / slidesToShow;
        
        slides.forEach((slide, index) => {
            slide.style.minWidth = `${itemWidth}px`;
            slide.style.transform = `translateX(-${currentIndex * itemWidth}px)`;
        });
    }

    function showNext() {
        if (currentIndex < slides.length - slidesToShow) {
            currentIndex++;
            updateSlider();
        }
    }

    function showPrev() {
        if (currentIndex > 0) {
            currentIndex--;
            updateSlider();
        }
    }

    nextButton.addEventListener('click', showNext);
    prevButton.addEventListener('click', showPrev);

    window.addEventListener('resize', updateSlider);

    // Initial update
    updateSlider();
});