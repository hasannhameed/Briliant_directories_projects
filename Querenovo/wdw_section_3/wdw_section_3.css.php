/* --- Testimonial Section Styles --- */
.testimonial-section-container {
    padding: 40px 15px;
    background-color: #fff;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
}

.ta-section-title {
    font-size: 24px;
    font-weight: 700;
    color: #000;
    margin-bottom: 25px;
}

.testimonial-card {
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
    height: 100%; /* Ensures all cards in a row are the same height */
    display: flex;
    flex-direction: column; /* Stacks content vertically */
    box-shadow: 0 1px 3px rgba(0,0,0,0.04);
}

.testimonial-author {
    display: flex;
    align-items: center;
    margin-bottom: 15px;
}

.testimonial-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    object-fit: cover;
    margin-right: 15px;
}

.testimonial-author-info h4 {
    font-size: 16px;
    font-weight: 600;
    margin: 0 0 3px 0;
    color: #000;
}

.testimonial-author-info p {
    font-size: 13px;
    color: #555;
    margin: 0;
}

.testimonial-rating {
    margin-bottom: 10px;
}

.testimonial-rating .fa-star {
    color: #00a680; /* Tripadvisor Green */
    font-size: 14px;
    margin-right: 2px;
}

.testimonial-title {
    font-size: 18px;
    font-weight: 700;
    color: #000;
    margin-top: 0;
    margin-bottom: 10px;
}

.testimonial-text {
    font-size: 14px;
    line-height: 1.6;
    color: #333;
    flex-grow: 1; /* Pushes the link to the bottom */
}

.reviewed-item-link {
    font-size: 14px;
    color: #555;
    margin-top: 15px;
    margin-bottom: 0;
}

.reviewed-item-link a {
    color: #000;
    font-weight: 600;
    text-decoration: none;
}

.reviewed-item-link a:hover {
    text-decoration: underline;
}

.more-reviews-link {
    margin-top: 10px;
}

.more-reviews-link a {
    font-size: 16px;
    font-weight: 600;
    color: #000;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
}

.more-reviews-link a:hover {
    text-decoration: underline;
}

.arrow-circle {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border: 1px solid #e0e0e0;
    border-radius: 50%;
    margin-left: 10px;
    transition: background-color 0.2s ease;
}

.more-reviews-link a:hover .arrow-circle {
    background-color: #f2f2f2;
}