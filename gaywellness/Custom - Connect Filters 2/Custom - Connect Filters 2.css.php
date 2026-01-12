.fa, .fas, .far, .fal, .fab, .fad {
    color: #3F7880 !important;
}

/* ===== MAIN "Their Interests" header ===== */
.gw-interests-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    cursor: pointer;
    margin-bottom: 6px;
}

.gw-interests-header i {
    color: #0d3f4f;
}

.gw-interests-arrow i {
    color: #3F7880; /* arrow color */
}

/* ===== groups ===== */
.gw-interest-group {
    margin-top: 10px;
}

.gw-interest-group-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    cursor: pointer;
}

.gw-interest-group-title {
    font-weight: 600;
    color: #0d3f4f;
}

.gw-interest-group-toggle i {
    color: #3F7880;  /* plus / minus color */
}

/* body containing the buttons */
.gw-interest-group-body {
    margin-top: 6px;
}

/* ===== pill buttons (full width, hover, etc.) ===== */
.gw-pill {
    display: inline-flex !important;
    align-items: center;
    justify-content: center;
    box-sizing: border-box;
    margin: 4px 0 !important;
    border-radius: 8px !important;
    border: 1px solid #0d3f4f;
    background-color: #ffffff;
    text-decoration: none;
    text-align: center;
    height: 36px !important;
    line-height: 1.2;
    padding: 0 12px;
    white-space: nowrap;
    color: #0d3f4f !important;
    font-weight: 400;
}

.gw-pill-full {
    width: 100% !important;
}

.gw-pill:hover {
    background-color: #0d3f4f;
    color: #ffffff !important;
    text-decoration: none;
}

/* Fix H3 so spans don’t shrink the text */
.gw-interests-header span {
    font-size: inherit !important;
    font-weight: inherit !important;
}

/* Default: everything collapsed */
.gw-interests-body {
    display: none;
}
.gw-interest-group-body {
    display: none;
}
