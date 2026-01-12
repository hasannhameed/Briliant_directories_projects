body {
            background: white;
            min-height: 100vh;
            padding: 40px 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .dashboard-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
        }

        .dashboard-title {
            text-align: center;
            color: #333;
            font-size: 2.5em;
            margin-bottom: 50px;
            font-weight: 300;
        }

        .dashboard-card {
            background: #AAE2D3;
            border-radius: 25px;
            padding: 40px 20px;
            margin-bottom: 30px;
            text-align: center;
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            cursor: pointer;
            position: relative;
            overflow: hidden;
            border: none;
            min-height: 180px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

 	.orange-overide {
            background: #FA5C2B!important;
            border-radius: 25px;
            padding: 20px 0px !important;
            margin-bottom: 30px !important;
            text-align: center;
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            cursor: pointer;
            position: relative;
            overflow: hidden;
            border: none;
            min-height: 80px !important;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
.blue-overide {
            background: #204254!important;
            border-radius: 25px;
	color: #ffffff !important;
            padding: 20px 0px !important;
            margin-bottom: 30px !important;
            text-align: center;
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            cursor: pointer;
            position: relative;
            overflow: hidden;
            border: none;
            min-height: 80px !important;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .dashboard-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255,255,255,0.1), transparent);
            transform: rotate(45deg);
            transition: all 0.5s;
            opacity: 0;
        }

        .dashboard-card:hover::before {
            animation: shine 0.6s ease-out;
        }

        @keyframes shine {
            0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); opacity: 0; }
            50% { opacity: 1; }
            100% { transform: translateX(100%) translateY(100%) rotate(45deg); opacity: 0; }
        }

        .dashboard-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 15px 40px rgba(0,0,0,0.25);
            background: #B8E8DB;
        }

        .dashboard-card:active {
            transform: translateY(-4px) scale(1.01);
            transition: all 0.1s;
        }

        .card-icon {
            font-size: 3em;
            color: #0D3F4F;
            margin-bottom: 15px;
            transition: all 0.3s ease;
        }

        .dashboard-card:hover .card-icon {
            transform: scale(1.1);
            color: #0D3F4F;
        }

        .card-title {
            font-size: 1.4em;
            font-weight: 600;
            color: #0D3F4F;
            margin: 0;
            text-transform: capitalize;
            letter-spacing: 0.5px;
        }

    .card-title-blue {
            font-size: 1.4em;
            font-weight: 600;
            color: #fff !important;
            margin: 0;
            text-transform: capitalize;
            letter-spacing: 0.5px;
        }

        .card-subtitle {
            font-size: 0.9em;
            color: #0D3F4F;
            margin-top: 8px;
            opacity: 0;
            transform: translateY(10px);
            transition: all 0.3s ease;
        }

 .card-subtitle-blue {
            font-size: 0.9em;
            color: #fff !important;
            margin-top: 8px;
            opacity: 0;
            transform: translateY(10px);
            transition: all 0.3s ease;
        }

        .dashboard-card:hover .card-subtitle {
            opacity: 1;
            transform: translateY(0);
        }

        /* Remove variant classes - all cards use same color */

        .action-button {
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .action-button:hover,
        .action-button:focus {
            text-decoration: none;
            color: inherit;
        }

        .mobile-only {
            display: none;
        }

        .desktop-space {
            display: inline;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .mobile-only {
                display: inline;
            }
            
            .desktop-space {
                display: none;
            }
            
            .dashboard-card {
                margin-bottom: 15px;
                min-height: 100px;
                max-height: 100px;
                padding: 15px 10px;
            }
            
            .card-icon {
                font-size: 1.5em !important;
                margin-bottom: 5px;
            }
            
            .fa-2x {
                font-size: 1.5em !important;
            }
            
            .card-title {
                font-size: 0.85em;
                line-height: 1.1;
                margin: 0;
            }
            
            .card-subtitle {
                display: none;
            }
            
            .dashboard-card:hover {
                transform: none;
                box-shadow: 0 8px 25px rgba(0,0,0,0.15);
                background: #AAE2D3;
            }
            
            .dashboard-card:hover .card-icon {
                transform: none;
            }
            
            .dashboard-card:hover .card-subtitle {
                opacity: 0;
                transform: translateY(10px);
            }
        }