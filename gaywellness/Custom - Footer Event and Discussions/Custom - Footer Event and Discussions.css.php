/* Prevent Bootstrap .row negative margins from causing horizontal overflow */
.gc-mobile-icons .gc-mobile-icons-row{
  margin-left: 0;
  margin-right: 0;
}

/* Equal left / center / right spacing */
.gc-mobile-icons .gc-mobile-icon-col{
  padding-left: 10px;
  padding-right: 10px;
}

/* Make each tile a true square and force the image to fill it consistently */
.gc-mobile-icons .gc-connect-icon-link{
  display: block;
  width: 100%;
  aspect-ratio: 1 / 1;
}

.gc-mobile-icons .gc-connect-icon-link img{
  display: block;
  width: 100%;
  height: 100%;
  object-fit: contain;   /* use cover if you prefer crop-fill */
  max-width: 100%;
}
