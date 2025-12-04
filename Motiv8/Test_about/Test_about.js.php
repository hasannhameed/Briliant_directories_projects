<script>
  // Sidebar toggle
document.getElementById("menuToggle").onclick = function() {
  document.getElementById("sidebarMenu").classList.add("active");
};

// Sidebar close
document.getElementById("menuClose").onclick = function() {
  document.getElementById("sidebarMenu").classList.remove("active");
};

</script>
<script>

document.addEventListener("DOMContentLoaded", () => {
  const images = document.querySelectorAll(".gallery-img");
  const lightbox = document.getElementById("lightbox");
  const lightboxImg = document.getElementById("lightbox-img");
  const counter = document.getElementById("counter");
  const closeBtn = document.querySelector(".lightbox .close");
  const prevBtn = document.querySelector(".lightbox .prev");
  const nextBtn = document.querySelector(".lightbox .next");

  let currentIndex = 0;

  images.forEach((img, i) => {
    img.addEventListener("click", () => {
      currentIndex = i;
      showImage();
    });
  });

  function showImage() {
    lightbox.classList.add("active");
    lightboxImg.src = images[currentIndex].src;
    counter.textContent = `${currentIndex + 1} / ${images.length}`;
  }

  function closeLightbox() {
    lightbox.classList.remove("active");
  }

  function changeSlide(dir) {
    currentIndex = (currentIndex + dir + images.length) % images.length;
    showImage();
  }

  closeBtn.addEventListener("click", closeLightbox);
  prevBtn.addEventListener("click", () => changeSlide(-1));
  nextBtn.addEventListener("click", () => changeSlide(1));

  lightbox.addEventListener("click", (e) => {
    if (e.target === lightbox) closeLightbox();
  });

  document.addEventListener("keydown", (e) => {
    if (!lightbox.classList.contains("active")) return;
    if (e.key === "ArrowRight") changeSlide(1);
    if (e.key === "ArrowLeft") changeSlide(-1);
    if (e.key === "Escape") closeLightbox();
  });
});


  
	  // Get all question buttons
  document.querySelectorAll(".faq-question").forEach(btn => {
    btn.addEventListener("click", () => {
      const answer = btn.nextElementSibling;
      const arrow = btn.querySelector(".arrow");

      // Toggle answer visibility
      if (answer.style.display === "block") {
        answer.style.display = "none";
        arrow.textContent = "▼";
      } else {
        // Close all open answers first (for proper tab behavior)
        document.querySelectorAll(".faq-answer").forEach(a => a.style.display = "none");
        document.querySelectorAll(".arrow").forEach(ar => ar.textContent = "▼");

        // Open the clicked one
        answer.style.display = "block";
        arrow.textContent = "▲";
      }
    });
  });

 
	  
	  </script> 
