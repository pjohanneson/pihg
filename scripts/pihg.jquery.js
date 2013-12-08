(function($) {
	$("a.add_row").click( function() {
		$('table#seed-info > tbody:last').append("\
<tr>\n\
	<td><input type='text' name='_pihg_seed_info[year]' /></td>\n\
	<td><input type='text' name='_pihg_seed_info[PA]' /></td>\n\
	<td><input type='text' name='_pihg_seed_info[SA]' /></td>\n\
	<td><input type='text' name='_pihg_seed_info[0A]' /></td>\n\
	<td><input type='text' name='_pihg_seed_info[LA]' /></td>\n\
	<td><input type='text' name='_pihg_seed_info[GLA]' /></td>\n\
	<td><input type='text' name='_pihg_seed_info[ALA]' /></td>\n\
	<td><input type='text' name='_pihg_seed_info[SDA]' /></td>\n\
	<td><input type='text' name='_pihg_seed_info[Oil]' /></td>\n\
</tr>");
	});
})(jQuery);