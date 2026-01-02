.overlay {
  position: relative;
  width: 50%;
}
.link{
color:#fff !important;
	text-decoration: none !important;
}
a:hover, a:active {
  text-decoration: none;
}
.image {
  opacity: 1;
  display: block;
  width: 100%;
  height: auto;
  transition: .5s ease;
  backface-visibility: hidden;
}

.middle {
  transition: .5s ease;
  opacity: 0;
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  -ms-transform: translate(-50%, -50%);
  text-align: center;
	width:100%;
}
.middle1 {
  transition: .5s ease;
  
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  -ms-transform: translate(-50%, -50%);
  text-align: center;
	width:100%;
}

.overlay:hover .image {
  opacity: 0.3;
}

.overlay:hover .middle {
  opacity: 1;
}

.textone {
  background-color: #4CAF50;
  color: white;
  font-size: 16px;
 padding: 16px 0px;
	margin:10px;
		

}