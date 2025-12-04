<script>
const btn_contact_action = document.querySelector('.btn-rappel');
const modal_title        = document.querySelector('.modal-title');
const btn_email          = document.querySelector('.btn-email');

btn_contact_action.addEventListener("click", function () {
    modal_title.textContent = "Demande de rappel";
});

btn_email.addEventListener("click", function () {
    modal_title.textContent = "Contactez un conseiller Quirenov'";
});

</script>