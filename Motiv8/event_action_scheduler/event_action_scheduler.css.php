.alertsuccess {
    color: black !important;
    font-weight: bold !important;
    background-color: #dff0d8;
    border-color: #d6e9c6;
}

.toggleImage{
	width: 20px;
    height: 20px;
    border-radius: 50%;
    margin-right: 8px;
}

.plugin-header{
	    display: flex;
    justify-content: space-between;
}

.highlight-row {
    background-color: #ffffcc !important;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
    transition: background-color 0.5s ease-in-out;
}


/* HTML: <div class="loader"></div> */
.loader {
  width: 20px;
	margin: 0 auto;
  aspect-ratio: .75;
  --c: no-repeat linear-gradient(#000 0 0);
  background: 
    var(--c) 0%   50%,
    var(--c) 50%  50%,
    var(--c) 100% 50%;
  animation: l7 1s infinite linear alternate;
}
@keyframes l7 {
  0%  {background-size: 20% 50% ,20% 50% ,20% 50% }
  20% {background-size: 20% 20% ,20% 50% ,20% 50% }
  40% {background-size: 20% 100%,20% 20% ,20% 50% }
  60% {background-size: 20% 50% ,20% 100%,20% 20% }
  80% {background-size: 20% 50% ,20% 50% ,20% 100%}
  100%{background-size: 20% 50% ,20% 50% ,20% 50% }
}