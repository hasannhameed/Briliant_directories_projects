.sec-head { 
	font-weight: 700; 
	font-size: 38px; 
	margin-bottom: 2px !important; 
	text-align: center;
	
} 
.section-subtitle { 
	font-size: 18px; 
	color: #606060; 
	margin-bottom: 50px; 
	text-align: center;
}


.feature-section {
  padding: 60px 0;
 
}

.feature-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 30px;
}

.feature-card {
  padding: 25px;
  border-radius: 12px;
  transition: 0.25s ease;
  background: #fff;
  cursor: default;
}

.feature-card:hover {
  box-shadow: 0 10px 25px rgba(0,0,0,0.12);
}

.feature-icon {
  display: flex;
  justify-content: flex-start;
  align-items: flex-start;
  margin-bottom: 18px;
  color : red;
}

.feature-card h3 {
  margin-bottom: 10px;
  font-size: 18px;
  line-height: 1.3;
  text-align: left;
}

.feature-card p {
  margin: 0;
  font-size: 15px;
  line-height: 1.5;
  text-align: left;
}

/* Responsive Breakpoint */
/* Title tweaks on small screens (optional but helpful) */
@media (max-width: 768px){
  .section-title { font-size: 30px; }
  .section-subtitle { font-size: 16px; margin-bottom: 30px; }
}

/* Grid stays responsive */
.feature-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 30px;
}
@media (max-width: 992px){
  .feature-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 600px){
  .feature-grid { grid-template-columns: 1fr; gap: 20px; }
  .feature-card { padding: 18px; }
}

/* Increase icon size to 34x34 and keep alignment */
.feature-icon svg{
  width: 34px !important;
  height: 34px !important;
}

/* (optional) slightly larger on very large screens */
@media (min-width: 1400px){
  .feature-icon svg{ width: 36px !important; height: 36px !important; }
}
