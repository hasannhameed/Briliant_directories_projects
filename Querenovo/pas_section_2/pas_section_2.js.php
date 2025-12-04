<script>

  const buttons = document.querySelectorAll(".ta-chip-btn");
  const sections = document.querySelectorAll(".ta-section[id]");
  const viewAllBtn = document.querySelector(".ta-chip-btn.view_all");

  function hideAllSections() {
    sections.forEach(section => section.style.display = "none");
  }

  function showAllSections() {
    sections.forEach(section => section.style.display = "block");
  }

  buttons.forEach(button => {
    button.addEventListener("click", function() {
      // If it's the "View All" button → show all sections
      if (this.classList.contains("view_all")) {
        showAllSections();
        buttons.forEach(btn => btn.classList.remove("active2"));
        this.classList.add("active2");
        return;
      }

      const classList = this.classList;
      let targetId = "";

      sections.forEach(section => {
        const id = section.id;
        if (classList.contains(id)) {
          targetId = id;
        }
      });

      if (targetId) {
        hideAllSections();
        document.getElementById(targetId).style.display = "block";
        //document.getElementById(targetId).scrollIntoView({ behavior: "smooth" });
      }

      buttons.forEach(btn => btn.classList.remove("active2"));
      this.classList.add("active2");
    });
  });

	  
	  let btn = document.querySelector('.ta-chip-btn');
btn.classList.add("active2");


</script>