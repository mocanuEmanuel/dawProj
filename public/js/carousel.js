// Simple Carousel Implementation
document.addEventListener('DOMContentLoaded', () => {
    const carousels = document.querySelectorAll('.carousel-container');

    carousels.forEach(container => {
        const track = container.querySelector('.carousel-track');
        const prevBtn = container.querySelector('.carousel-prev');
        const nextBtn = container.querySelector('.carousel-next');
        const cards = container.querySelectorAll('.book-card');

        if (!track || !cards.length) return;

        let currentIndex = 0;
        const cardWidth = cards[0].offsetWidth + 20; // width + gap
        const visibleCards = Math.floor(container.offsetWidth / cardWidth);

        nextBtn.addEventListener('click', () => {
            if (currentIndex < cards.length - visibleCards) {
                currentIndex++;
                updateCarousel();
            }
        });

        prevBtn.addEventListener('click', () => {
            if (currentIndex > 0) {
                currentIndex--;
                updateCarousel();
            }
        });

        function updateCarousel() {
            const translateX = -(currentIndex * cardWidth);
            track.style.transform = `translateX(${translateX}px)`;
        }
    });
});
