.section-title {
    font-weight: 700;
    font-size: 38px;
    margin-bottom: 60px;
    text-align: center;
}

/* Card Base */
.card-box {
    display: block;
    background: #fff;
    border: 1px solid #eee;
    border-radius: 16px;
    padding: 20px;
    text-decoration: none !important;
    transition: all 0.25s ease;
    min-height: 220px;
    position: relative;
    overflow: hidden;
    cursor: pointer;
}
.card-box:hover {
    border-color: #d90429;
    box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    transform: translateY(-4px);
}
.card-box:hover h4 { color: red; }

/* Icon Container */
.card-icon {
    width: 55px;
    height: 55px;
    border-radius: 12px;
    background: #eef4ff;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 15px;
    transition: transform 0.25s ease;
}
.card-box:hover .card-icon { transform: scale(1.15); }
.card-icon i { font-size: 26px; color: #0d6efd; }

/* Text */
.card-box h4 {
    font-weight: 600;
    margin-bottom: 10px;
    color: #0b1a33;
}
.card-box p {
    margin-bottom: 15px;
    color: #444;
}

/* Link CTA */
.card-link {
    color: #d90429 !important;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
}
.card-link i {
    margin-left: 5px;
    transition: transform 0.25s ease;
}
.card-box:hover .card-link i {
    transform: translateX(4px);
}

/* Icon Variants */
.icon-box {
    width: 56px;
    height: 56px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 16px;
}
.icon-blue { background: #E7EDFF; }
.icon-purple { background: #F3E8FF; }
.icon-green { background: #E6FFEF; }
.icon-orange { background: #FFEDE1; }

/* -----------------------------------
   ✅ RESPONSIVE ECOSYSTEM CARDS LAYOUT
   (SAFE FOR BRILLIANT DIRECTORIES)
----------------------------------- */

/* Apply only to the ecosystem section row */
.ecosystem-grid {
    display: flex;
    flex-wrap: wrap;
    justify-content: flex-start;
    margin-left: -10px;
    margin-right: -10px;
}
.ecosystem-grid > .col-sm-3 {
    padding-left: 10px;
    padding-right: 10px;
    box-sizing: border-box;
    flex: 0 0 25%; /* Desktop: 4 per row */
    max-width: 25%;
}

/* Tablet: 2 per row */
@media (max-width: 992px) {
    .ecosystem-grid > .col-sm-3 {
        flex: 0 0 50%;
        max-width: 50%;
    }
}

/* Mobile: 1 per row */
@media (max-width: 600px) {
    .ecosystem-grid > .col-sm-3 {
        flex: 0 0 100%;
        max-width: 100%;
    }
}
