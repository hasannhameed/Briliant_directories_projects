/* Container */
.multi-pill-row{
  display:flex !important;
  flex-wrap:nowrap !important;
  gap:6px !important;
  overflow-x:auto !important;
  overflow-y:hidden !important;
  -webkit-overflow-scrolling:touch;
  align-items:center !important;
}

/* Pill */
.multi-pill{
  display:inline-flex !important;
  align-items:center !important;
  justify-content:center !important;

  flex:0 0 auto !important;
  align-self:center !important;

  background-color:#0D3F4F !important;
  color:#FFFFFF !important;

  font-family:'Nunito',sans-serif;
  font-weight:700;
  font-size:20px;

  padding:8px 15px;
  border-radius:9999px;

  white-space:nowrap !important;
  line-height:1 !important;
  text-decoration:none !important;
}

/* Text never wraps */
.multi-pill-text{
  white-space:nowrap !important;
  line-height:1 !important;
}

/* Font Awesome icon */
.multi-pill i{
  font-size:1em !important;
  margin-right:8px !important;
  line-height:1 !important;
  flex:0 0 auto !important;
}

/* Inline SVG icon wrapper: fixed icon box */
.multi-pill-icon{
  width:20px !important;
  height:20px !important;
  margin-right:8px !important;
  display:inline-flex !important;
  align-items:center !important;
  justify-content:center !important;
  flex:0 0 20px !important;
}

/* Force the actual svg to behave like an icon (not a big image) */
.multi-pill-icon svg{
  width:20px !important;
  height:20px !important;
  display:block !important;
}

/* HOVER colors (locked back in) */
.multi-pill:hover{
background-color:#CEF6ED !important;
  color:#0D3F4F !important;
}

/* FA icon hover */
.multi-pill:hover i{
  color:#0D3F4F !important;
}

/* Inline SVG hover: recolor the white svg to teal using filter */
.multi-pill:hover .multi-pill-icon svg{
  filter: brightness(0) saturate(100%) invert(20%) sepia(25%) saturate(600%) hue-rotate(150deg) !important;
}

/* Mobile sizing */
@media (max-width:520px){
  .multi-pill{ font-size:16px; padding:6px 12px; }
  .multi-pill-icon, .multi-pill-icon svg{
    width:16px !important;
    height:16px !important;
    flex-basis:16px !important;
  }
}
