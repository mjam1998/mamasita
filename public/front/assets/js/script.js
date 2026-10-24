/* ================================================
   ماماسیتا – Slider Logic (button-only, no auto-play)
   ================================================ */

   function initSlider(trackId, prevBtnId, nextBtnId) {
    const track = document.getElementById(trackId);
    const prevBtn = document.getElementById(prevBtnId);
    const nextBtn = document.getElementById(nextBtnId);
  
    if (!track || !prevBtn || !nextBtn) return;
  
    let currentIndex = 0;
  
    function getCardWidth() {
      const card = track.children[0];
      if (!card) return 0;
      const gap = 20; // 1.25rem gap
      return card.offsetWidth + gap;
    }
  
    function getVisibleCount() {
      return Math.floor(track.parentElement.offsetWidth / getCardWidth()) || 1;
    }
  
    function getMaxIndex() {
      return Math.max(0, track.children.length - getVisibleCount());
    }
  
    function goTo(index) {
      const max = getMaxIndex();
      currentIndex = Math.max(0, Math.min(index, max));
      // RTL: slide direction is reversed
      const offset = currentIndex * getCardWidth();
      track.style.transform = `translateX(${offset}px)`;
  
      prevBtn.disabled = currentIndex === 0;
      nextBtn.disabled = currentIndex >= max;
      prevBtn.style.opacity = currentIndex === 0 ? '0.4' : '1';
      nextBtn.style.opacity = currentIndex >= max ? '0.4' : '1';
    }
  
    // RTL: "prev" (right arrow) goes to lower index (more items shown on right)
    prevBtn.addEventListener('click', () => goTo(currentIndex - 1));
    nextBtn.addEventListener('click', () => goTo(currentIndex + 1));
  
    // Initial state
    goTo(0);
  
    // Re-calculate on resize
    let resizeTimer;
    window.addEventListener('resize', () => {
      clearTimeout(resizeTimer);
      resizeTimer = setTimeout(() => goTo(0), 200);
    });
  }
  
  // Init both sliders
  document.addEventListener('DOMContentLoaded', () => {
    initSlider('coursesTrack', 'coursesPrev', 'coursesNext');
    initSlider('blogTrack', 'blogPrev', 'blogNext');
  });
  
  