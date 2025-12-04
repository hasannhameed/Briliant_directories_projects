<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<div class="form-group">
    <div class="col-sm-3 text-right norpad">
        <label class="control-label bd-" for="member_listing_details_324-element-10">Year Established</label>
    </div>
    <div class="col-sm-9">
        <select name="experience" autocomplete="off" class="form-control" id="year">
            <option value="" disabled selected>Select a year</option>
        </select>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        let currentYear = new Date().getFullYear();
        let years = [];
        
        for (let year = 1500; year <= currentYear; year++) {
            years.push(year);
        }
        
        let savedCustomYear = localStorage.getItem('customYear');
        if (savedCustomYear) {
            years.push(savedCustomYear); 
        }

        let selectedYear = "<?php echo $user_data['experience']; ?>"; 
        
        $('#year').select2({
            data: years.map(function(year) {
                return { id: year, text: year.toString() };
            }),
            placeholder: "Select a year",
            allowClear: true,
            minimumResultsForSearch: 0, 
        });
        
        if (selectedYear) {
            $('#year').val(selectedYear).trigger('change'); 
        } else {
            $('#year').val(null).trigger('change');
        }

        $('#year').on('change', function() {
            let selectedYear = $(this).val();
            if (selectedYear && selectedYear < 1500) {
                localStorage.setItem('customYear', selectedYear); 
            }
        });

        $('#year').on('select2:open', function() {
            let searchField = $('.select2-search__field');
            searchField.on('input', function() {
                let typedValue = $(this).val();
                if (typedValue && typedValue < 1500) {
                    if (!isNaN(typedValue) && typedValue.trim() !== '') {
                        localStorage.setItem('customYear', typedValue); 
                    }
                }
            });
        });

        $('#year').on('select2:select', function(e) {
            let selectedYear = e.params.data.id;
            if (selectedYear && selectedYear < 1500) {
                localStorage.setItem('customYear', selectedYear); 
            }
        });
    });
</script>