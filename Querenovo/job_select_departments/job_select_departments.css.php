/* Hide dropdown list by default */
        .dep-pro-wrapper {
        position: relative;
    }

    .dep-pro-input {
        border-radius: 10px;
        padding: 10px 12px;
    }

    .dep-pro-dropdown {
        display: none;
        position: absolute;
        z-index: 999;
        width: 100%;
        max-height: 260px;
        overflow-y: auto;
        background: #fff;
        border: 1px solid #e6e6e6;
        border-radius: 12px;
        margin-top: 6px;
        box-shadow: 0 10px 25px rgba(0,0,0,.08);
    }

    .dep-pro-option {
        padding: 10px 14px;
        cursor: pointer;
        display: flex;
        gap: 8px;
        align-items: center;
        font-size: 14px;
    }

    .dep-pro-option strong {
        color: #111;
        min-width: 15px;
    }

    .dep-pro-option span {
        color: #555;
    }

    .dep-pro-option:hover {
        background: #f5f7fa;
    }