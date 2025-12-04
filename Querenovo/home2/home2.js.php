<script>
document.addEventListener("DOMContentLoaded", function() {
  const faqBox = document.querySelector(".faq-box");
  if (!faqBox) return;

  const paragraphs = faqBox.querySelectorAll("p");
  paragraphs.forEach(p => {
    const strong = p.querySelector("strong");
    if (!strong) return;

    
    const content = document.createElement("div");
    content.className = "faq-content";
    content.innerHTML = p.innerHTML.replace(/<strong>.*?<\/strong><br>/, "");

    const wrapper = document.createElement("div");
    wrapper.className = "faq-item";
    wrapper.appendChild(strong);
    wrapper.appendChild(content);

    faqBox.replaceChild(wrapper, p);

   
    strong.addEventListener("click", () => {
      const isOpen = wrapper.classList.contains("open");
      faqBox.querySelectorAll(".faq-item").forEach(item => {
        item.classList.remove("open");
        item.querySelector("strong").classList.remove("active");
      });
      if (!isOpen) {
        wrapper.classList.add("open");
        strong.classList.add("active");
      }
    });
  });
});
	
	

	


	
	
</script>