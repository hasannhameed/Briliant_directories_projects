<?php if ($user[subscription_id] != '87') { ?>


<a class="btn-md" rel="nofollow" target="_blank" href="http://maps.google.com/maps?daddr=<?php if ($user['lat'] != '' && $user['lon']!=''){ echo $user['lat'] . ',' . $user['lon'] . '&saddr=' . $_SESSION['vdisplay'];  }else{ echo $user['gmap'] . '&saddr=' . $_SESSION['vdisplay']; } ?>" title="Click for directions">
            %%%get_directions_label%%% <i class="fa fa-external-link" aria-hidden="true"></i>
        </a><br><br>
 <?php } ?>