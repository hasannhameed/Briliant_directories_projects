body {
  font-family: "Segoe UI", sans-serif;
  text-align: center;
  background: #f5f7fa;
  color: #333;
  margin: 0;
  padding: 0;
}

.map-title {
  margin: 20px 0;
  font-size: 24px;
  color: #2c3e50;
}

.map-container {
  display: flex;
  justify-content: center;
  align-items: center;
  margin: 30px auto;
  width: 80%;
  max-width: 900px;
  height: auto;
  border: 2px solid #ddd;
  background: #fff;
  border-radius: 10px;
  padding: 10px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}

/* Hover style for SVG paths */
svg path {
  cursor: pointer;
  transition: all 0.3s ease;
  fill: #cbd5e1;
  stroke: #64748b;
  stroke-width: 0.5;
}

svg path:hover {
  fill: #3498db;
  stroke: #1e40af;
}
