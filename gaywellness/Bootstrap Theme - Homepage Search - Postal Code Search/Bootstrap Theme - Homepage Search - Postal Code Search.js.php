<script >
    let input = document.querySelector(".input");
    let input3 = document.querySelector(".input3");
let button = document.querySelector(".button");
	
	if (button) {
		
		button.disabled = true; //setting button state to disabled

		input.addEventListener("change", stateHandle);
input3.addEventListener("change", stateHandle3);

	}
	

function stateHandle3() {
    if (document.querySelector(".input3").value === "") {
        button.disabled = true; //button remains disabled
    } else {
        button.disabled = false; //button is enabled
    }
}
function stateHandle() {
    if (document.querySelector(".input").value === "") {
        button.disabled = true; //button remains disabled
    } else {
        button.disabled = false; //button is enabled
    }
}
</script>