/* ============ SIDEBAR WRAPPER ============ */
.gw-filter-sidebar {
    font-family: 'Nunito', sans-serif;
    color: #0d3f4f;
}

.member_results h2 span, .member_results h3 span {
    color: #3F7880 !important;
    font-size: .6em;
}

/* ============ SECTION / H3 HEADERS ============ */
.gw-filter-section {
    margin-bottom: 18px;
    padding-bottom: 8px;
    border-bottom: 1px solid #f0f0f0;
}

/* Remove padding + border from the last filter section */
.gw-filter-section:last-child {
    padding-bottom: 0 !important;
	margin-bottom: 0px !important;
    border-bottom: none !important;
}


.gw-filter-header {
    font-size: 18px;
    font-weight: 600;
    margin: 0;
    padding: 6px 0;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: space-between;
    color: #0d3f4f;
}

/* make ALL spans inside the H3 inherit the H3 size & weight */
.gw-filter-header span {
    font-size: inherit !important;
    font-weight: inherit !important;
}

.gw-filter-prefix {
    font-size: 18px;
    margin-right: 6px;
    color: #0d3f4f;
}

/* arrow color #3F7880 */
.gw-filter-toggle-icon {
    font-size: 14px;
    color: #3F7880 !important;
}

/* ============ COLLAPSIBLE BODY ============ */
.gw-filter-body {
    display: none;              /* start closed */
    margin-top: 6px;
    text-align: center !important;
}

/* ============ PILLS (ADDITIVE TO custom-sidebar-search-filters) ============ */
/* DO NOT touch .custom-sidebar-search-filters globally */


.gw-pill {
    display: inline-flex !important;      /* use flex to center vertically */
    align-items: center;                  /* vertical text centering */
    justify-content: center;              /* horizontal centering */
    box-sizing: border-box;
    margin: 4px 0 !important;
    border-radius: 8px !important;        /* updated */
    border: 1px solid #0d3f4f;
    background-color: #ffffff;
    text-decoration: none;
    text-align: center;
    height: 36px !important;              /* updated fixed height */
    line-height: 1.2;                     /* prevents vertical cutoff */
    padding: 0 12px;                      /* comfortable horizontal spacing */
    white-space: nowrap;                  /* keep text on one line */
	font-size:19px;
}

.gw-pill:hover {
    background-color: #0d3f4f;
    color: #ffffff !important;
    text-decoration: none;
}

/* full-width pills (both sections now) */
.gw-pill-full {
    width: 100% !important;
}













    .member_results .custom-sidebar-search-filters1 {
        border-bottom: 1px solid #f9f9f9;
        border-radius: 0px;
        margin-bottom: 0px;
	    padding-bottom: 0px;
    }

    .member_results .custom-sidebar-search-filters {
        border-top: 1px solid #f9f9f9;
        border-radius: 0px;
    }

    . {
        border-bottom: 0px solid #f9f9f9;
        border-radius: 0px;
    }

    .fa,
    .fas,
    .far,
    .fal,
    .fab,
    .fad {
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
        color: #3F7880;
        /* arrow color */
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
        color: #3F7880;
        /* plus / minus color */
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
