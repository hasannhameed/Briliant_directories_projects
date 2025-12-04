<div class="form-group">
  <label class="control-label">Attending Staff Invitation Deadline</label>

  <!-- 👁️ Visible to user -->
  <input type="text"
         placeholder="DD/MM/YYYY"
         title="DD/MM/YYYY"
         pattern="^[0-9]{2}\/[0-9]{2}\/[0-9]{4}$"
         name="invitation_deadline"
         id="invitation-deadline"
         class="form-control half-width">
</div>
<script>


	
document.addEventListener("DOMContentLoaded", function () {
    const startDateInput = document.getElementById("stardatepicker");
    const visibleDeadlineInput = document.getElementById("invitation_deadline_visible");
    const hiddenDeadlineInput = document.getElementById("invitation_deadline_hidden");
    const submitBtn = document.getElementById("submit-btn");

    function formatDateToDDMMYYYY(date) {
        const dd = String(date.getDate()).padStart(2, '0');
        const mm = String(date.getMonth() + 1).padStart(2, '0');
        const yyyy = date.getFullYear();
        return `${dd}/${mm}/${yyyy}`;
    }

    function parseDDMMYYToDate(ddmmyy) {
        const [day, month, yearShort] = ddmmyy.split('/');
        const year = parseInt(yearShort) < 50 ? `20${yearShort}` : `19${yearShort}`; // Adjust if needed
        const date = new Date(`${year}-${month}-${day}`);
        return isNaN(date) ? null : date;
    }

    function getCalculatedDeadline() {
        const rawStartDate = startDateInput.value.trim(); // e.g., 07/05/25
        const parsedDate = parseDDMMYYToDate(rawStartDate);
        if (!parsedDate) {
            console.warn("Invalid Start Date");
            return '';
        }
        parsedDate.setDate(parsedDate.getDate() - 8);
        return formatDateToDDMMYYYY(parsedDate);
    }


});
</script>