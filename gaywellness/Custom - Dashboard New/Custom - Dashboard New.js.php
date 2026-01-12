<!-- Bootstrap 3.7 JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.4.1/js/bootstrap.min.js"></script>
    
    <script>
        function handleCardClick(cardType) {
            // Add a visual feedback
            event.target.style.transform = 'scale(0.95)';
            setTimeout(() => {
                event.target.style.transform = '';
            }, 100);
            
            // Handle the click action
            console.log('Card clicked:', cardType);
            alert('Opening ' + cardType.replace('-', ' ').toUpperCase() + ' section...');
            
            // In a real application, you would navigate to the appropriate section:
            // window.location.href = '/dashboard/' + cardType;
        }

        // Add some interactive effects
        $(document).ready(function() {
            $('.dashboard-card').on('mouseenter', function() {
                $(this).find('.card-icon').addClass('animated');
            });
            
            $('.dashboard-card').on('mouseleave', function() {
                $(this).find('.card-icon').removeClass('animated');
            });
        });
    </script>