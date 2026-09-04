/**
 * LectorThema - Touch & Drag Responsive Hero Slider
 */
document.addEventListener('DOMContentLoaded', () => {
  const slider = document.querySelector('.hero-slider-container');
  if (!slider) return;

  const track = slider.querySelector('.hero-slides-track');
  const slides = slider.querySelectorAll('.hero-slide');
  const prevBtn = slider.querySelector('.hero-slider-prev');
  const nextBtn = slider.querySelector('.hero-slider-next');
  const dots = slider.querySelectorAll('.hero-dot');

  if (!slides.length) return;

  let currentIndex = 0;
  let autoplayTimer = null;
  let startX = 0;
  let currentX = 0;
  let isDragging = false;

  // Estado inicial: solo la primera diapositiva visible para evitar filtraciones de sombra o bordes
  slides.forEach((slide, idx) => {
    slide.style.visibility = idx === 0 ? 'visible' : 'hidden';
  });

  function updateSlider(index) {
    if (index < 0) index = slides.length - 1;
    if (index >= slides.length) index = 0;

    // Hacer visible la diapositiva destino antes del desplazamiento
    slides[index].style.visibility = 'visible';
    currentIndex = index;

    track.style.transform = `translateX(-${currentIndex * 100}%)`;

    dots.forEach((dot, idx) => {
      dot.classList.toggle('active', idx === currentIndex);
    });
  }

  // Al finalizar la transición, ocultar diapositivas inactivas
  track.addEventListener('transitionend', () => {
    slides.forEach((slide, idx) => {
      slide.style.visibility = idx === currentIndex ? 'visible' : 'hidden';
    });
  });

  function nextSlide() {
    updateSlider(currentIndex + 1);
  }

  function prevSlide() {
    updateSlider(currentIndex - 1);
  }

  if (nextBtn) nextBtn.addEventListener('click', () => { nextSlide(); resetAutoplay(); });
  if (prevBtn) prevBtn.addEventListener('click', () => { prevSlide(); resetAutoplay(); });

  dots.forEach((dot, idx) => {
    dot.addEventListener('click', () => {
      updateSlider(idx);
      resetAutoplay();
    });
  });

  // Touch / Mobile Swipe Support
  slider.addEventListener('touchstart', (e) => {
    startX = e.touches[0].clientX;
    isDragging = true;
    clearInterval(autoplayTimer);
    slides.forEach(slide => { slide.style.visibility = 'visible'; });
  }, { passive: true });

  slider.addEventListener('touchmove', (e) => {
    if (!isDragging) return;
    currentX = e.touches[0].clientX;
  }, { passive: true });

  slider.addEventListener('touchend', () => {
    if (!isDragging) return;
    isDragging = false;
    const diff = startX - currentX;
    if (Math.abs(diff) > 45) {
      if (diff > 0) {
        nextSlide();
      } else {
        prevSlide();
      }
    } else {
      slides.forEach((slide, idx) => {
        slide.style.visibility = idx === currentIndex ? 'visible' : 'hidden';
      });
    }
    startAutoplay();
  });

  // Autoplay
  function startAutoplay() {
    clearInterval(autoplayTimer);
    autoplayTimer = setInterval(nextSlide, 5500);
  }

  function resetAutoplay() {
    clearInterval(autoplayTimer);
    startAutoplay();
  }

  slider.addEventListener('mouseenter', () => clearInterval(autoplayTimer));
  slider.addEventListener('mouseleave', () => startAutoplay());

  startAutoplay();
});
