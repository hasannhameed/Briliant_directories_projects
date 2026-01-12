<!-- ICON BAR WITH MINT LINES -->

	<table style="width:100%;
                  border-top:2px solid #b7e8df;
                  border-bottom:2px solid #b7e8df;
                  ">
		<tbody>
			<tr>
				<!-- Settings -->
				<td style="text-align:center; padding:10px 0;">
    <a href="#" onclick="gcToggleMobileFilters(); return false;">
        <img src="/images/icons/connect-settings.png"
             alt="Settings"
             style="height: 26px; width: auto;"
             class="fr-fic fr-dii">
    </a>
</td>
				<!-- Locations -->
				<td style="text-align:center; padding:10px 0;">
					[widget=Block - Listings Map - client-mobile]</td>
				<!-- Calendar -->
				<td style="text-align:center; padding:10px 0;">
					<a href="/connect-events?category%5B%5D=<?php echo $profs['city_name']; ?>"><img src="/images/icons/connect-calendar.png" alt="Calendar" style="height: 26px; width: auto;" class="fr-fic fr-dii"></a></td>
				<!-- Chat -->
				<td style="text-align:center; padding:10px 0;">
					<a href="/discussions?category%5B%5D=<?php echo $profs['city_name']; ?>"><img src="/images/icons/connect-chat.png" alt="Chat" style="height: 26px; width: auto;" class="fr-fic fr-dii"></a></td>
				<!-- Online -->
				<td style="text-align:center; padding:10px 0;">
					<a href="?available_now=1"><img src="/images/icons/connect-online.png" alt="Online" style="height: 26px; width: auto;" class="fr-fic fr-dii"></a></td>
			</tr>
		</tbody>
	</table>