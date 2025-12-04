<script>

function validate() {
var reason='';
form=document.matched;
    

  if (form.answerq.value!=<? $total=$val1+$val2; echo $total; ?>) {
  	reason=reason+'- Answer the security question\n';
  }

if (reason!='') { reason='Please correct the following errors:\n'+reason; alert(reason); return false; }
else { document.getElementById('loadingimg2').style.visibility='visible'; return true; }
}

</script>