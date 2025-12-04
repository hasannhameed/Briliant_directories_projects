<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>France Map - Departments</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>
  <h1 class="map-title">France Map – Click a Department</h1>
  <div class="map-container">
    <!-- France SVG Map from Wikimedia (Simplified for clarity) -->
    <object id="france-map" type="image/svg+xml" data="https://upload.wikimedia.org/wikipedia/commons/3/37/Blank_map_of_France_departments.svg"></object>
  </div>

  <script src="script.js"></script>
</body>
</html>
<script>
    document.addEventListener("DOMContentLoaded", () => {
  const mapObject = document.getElementById("france-map");

  mapObject.addEventListener("load", () => {
    const svgDoc = mapObject.contentDocument;
    const paths = svgDoc.querySelectorAll("path");

    paths.forEach(path => {
      // Hover title (shows department name)
      const deptName = path.getAttribute("title") || path.id;
      if (deptName) {
        path.addEventListener("mouseenter", () => {
          path.style.fill = "#3498db";
        });
        path.addEventListener("mouseleave", () => {
          path.style.fill = "#cbd5e1";
        });

        // On click, redirect to a department page (customize URL)
        path.addEventListener("click", () => {
          const formatted = deptName.replace(/ /g, "-").toLowerCase();
          window.location.href = `https://example.com/departments/${formatted}`;
        });
      }
    });
  });
});

</script>