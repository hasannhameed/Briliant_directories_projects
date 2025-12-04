
/* Responsive behavior for <= 991px */
@media (max-width: 991px) {
  .froala-table,
  .froala-table tbody,
  .froala-table tr,
  .froala-table td {
    display: block;
    width: 100% !important;
  }

  /* Reduce heading text size */
  .froala-table h2 {
    font-size: 40px !important;
    text-align: center;
    margin-bottom: 30px !important;
  }

  /* Adjust paragraph text */
  .froala-table p,
  .froala-table div {
    font-size: 16px !important;
    line-height: 1.6;
    text-align: justify;
  }

  /* Image responsiveness */
  .froala-table img {
    width: 100% !important;
    height: auto !important;
    margin-top: 20px;
  }
}
.custom-navbar {
      background: #fff;
      border-bottom: 1px solid #eee;
      padding: 80px 0px;
      position: relative;
	 
    }

    /* Left hamburger */
    #menuToggle {
      font-size: 24px;
      background: none;
      border: none;
      outline: none;
    }

    /* Centered brand */
    .navbar-brand-centered {
      position: absolute;
      left: 50%;
      transform: translateX(-50%);
    }
@media (min-width: 768px) {
    .navbar-brand-centered img {
      max-height: 100px;
    margin-top: -24px;
    }
}
    /* Right dropdown user */
    .dropdown .btn img {
      height: 28px;
      width: 28px;
    }

   /* Sidebar base */
.sidebar {
  height: 100%;
  width: 0; /* Hidden by default */
  position: fixed;
  top: 0;
  left: 0;
  background: #fff;
  box-shadow: 2px 0 10px rgba(0,0,0,0.1);
  overflow-x: hidden;
  transition: 0.3s;
  z-index: 1050;
  padding-top: 60px;
}

/* Sidebar open state */
.sidebar.active {
  width: 280px; /* Adjust width */
}

/* Close button */
.sidebar .close-btn {
  position: absolute;
  top: 15px;
  right: 20px;
  font-size: 28px;
  border: none;
  background: none;
  cursor: pointer;
  color: #333;
}

/* Menu list */
.sidebar .menu-list {
  list-style: none;
  padding: 0;
  margin: 0;
}

.sidebar .menu-list li {
  margin: 12px 0;
}

.sidebar .menu-list li a {
  display: block;
  padding: 10px 20px;
  font-size: 16px;
  font-weight: 600;
  color: #111;
  text-transform: uppercase;
  transition: all 0.2s ease-in-out;
}

/* Hover effect */
.sidebar .menu-list li a:hover {
  background: #f5f5f5;
  padding-left: 28px;
  color: #007bff;
  text-decoration: none;
}

/* Active link (current page) */
.sidebar .menu-list li a.active {
  color: #007bff;
  border-left: 4px solid #007bff;
  background: #f0f8ff;
}


    @media (max-width: 767px) {
      .navbar-brand-centered img {
        min-height: 50px;
	margin-top:-17px;
      }
    }


.section-1 {
  position: relative;
  border-top: 1px solid #ccc;
}

/* Responsive arrow */
.section-1:before {
  content: "";
  position: absolute;
  left: 50%;
  transform: translateX(-50%);
  top: -1px; /* aligns with border-top */
  
  /* Responsive triangle */
  border-left: 3vw solid transparent;   /* left side */
  border-right: 3vw solid transparent;  /* right side */
  border-top: 2vw solid #f8f1f1;        /* arrow color */
}

/* Optional: limit size so it doesn’t get too big */
@media (min-width: 1200px) {
  .section-1:before {
    border-left: 40px solid transparent;
    border-right: 40px solid transparent;
    border-top: 30px solid #f8f1f1;
  }
}

@media (max-width: 600px) {
  .section-1:before {
    border-left: 20px solid transparent;
    border-right: 20px solid transparent;
    border-top: 15px solid #f8f1f1;
  }
}

.footer {
            position: relative;
            background: #f2f2f2;
            padding: 40px 20px;
            text-align: center;
            font-family: Arial, sans-serif;
        }
       
        /* Arrow/Triangle at top of footer */
        .footer::before {
            content: '';
            position: absolute;
            top: -20px;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 0;
            border-left: 25px solid transparent;
            border-right: 25px solid transparent;
            border-bottom: 20px solid #f2f2f2;
        }
       
        /* Social Icons */
        .social-icons {
            margin-bottom: 15px;
        }
       
        .social-icons a {
            text-decoration: none;
        }
       
        .social-icons img {
            width: 28px;
            height: 28px;
        }
       
        /* Copyright Text */
        .copyright {
            font-size: 14px;
            color: #555;
            margin: 0 0 15px 0;
            letter-spacing: 0.5px;
        }
       
        /* Footer Links */
        .footer-links {
            margin-bottom: 25px;
        }
       
        .footer-links a {
            font-size: 15px;
            color: #000;
            margin: 0 10px;
            text-decoration: none;
        }
       
        /* Divider */
        .divider {
            width: 60px;
            height: 1px;
            background: #555;
            margin: 0 auto 20px auto;
        }
       
        /* Powered By Section */
        .powered-by {
            font-size: 13px;
            color: #666;
            margin-bottom: 10px;
            letter-spacing: 1px;
        }
       
        .logo img {
            height: 30px;
        }
       
        /* Responsive Design */
        @media (max-width: 768px) {
            .footer {
                padding: 30px 15px;
            }
           
            .footer::before {
                border-left-width: 20px;
                border-right-width: 20px;
                border-bottom-width: 16px;
                top: -16px;
            }
           
            .footer-links a {
                display: inline-block;
                margin: 5px 8px;
                font-size: 14px;
            }
           
            .copyright {
                font-size: 13px;
            }
        }
       
        @media (max-width: 480px) {
            .footer-links a {
                display: block;
                margin: 8px 0;
            }
        }




section { padding:60px 20px; background:#fff; }
  h2 { text-align:center; font-size:32px; margin-bottom:30px; }

  /* Gallery Grid */
 .gallery.container {
  display: flex;
  flex-wrap: wrap;
  gap: 15px;
  justify-content: center;
}
.gallery-img {
  height: 310px;
  object-fit: cover;   /* crop nicely */
  border-radius: 8px;
  cursor: pointer;
}

.lightbox {
  display: none;
  position: fixed;
  z-index: 9999;
  top: 0; left: 0; right: 0; bottom: 0;
  background: rgba(0,0,0,0.9);
  justify-content: center;
  align-items: center;
  flex-direction: column;
  color: white;
}
.lightbox.active { display: flex; }
.lightbox img {
  max-width: 80%;
  max-height: 80%;
  border-radius: 8px;
}
.lightbox .close, .lightbox .prev, .lightbox .next {
  position: absolute;
  font-size: 40px;
  cursor: pointer;
  user-select: none;
}
.lightbox .close { top: 20px; right: 30px; }
.lightbox .prev { left: 30px; top: 50%; transform: translateY(-50%); }
.lightbox .next { right: 30px; top: 50%; transform: translateY(-50%); }
.counter { margin-top: 10px; font-size: 18px; }

 
  /* Controls */



 .offcanvas-start {
      width: 260px;
      background: #f8f9fa;
      border-right: 1px solid #ddd;
      position: fixed;
      top: 0;
      left: -260px;
      height: 100%;
      z-index: 1050;
      transition: left 0.3s ease-in-out;
      overflow-y: auto;
    }
    .offcanvas-start.active {
      left: 0;
    }
    .offcanvas-header {
      padding: 15px;
      border-bottom: 1px solid #ccc;
    }
    .offcanvas-title {
      margin: 0;
      font-size: 18px;
      font-weight: bold;
    }
    .btn-close {
      background: none;
      border: none;
      font-size: 22px;
      float: right;
    }
    .offcanvas-body ul li a {
      font-weight: 500;
      padding: 10px 15px;
      display: block;
      color: #333;
      transition: 0.2s;
    }
    .offcanvas-body ul li a:hover {
      background: #eee;
    }