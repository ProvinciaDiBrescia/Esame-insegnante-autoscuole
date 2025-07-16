<?php session_start(); ?>
<div class="menu">
	<ul id="menu">
		<li><a href="index.php" class="homepage">&nbsp;</a></li>
		<?php if ($_SESSION['userinfo']['profilo'] == 3) { ?>
		<li><span class="admin clear">&nbsp;</span>
			<ul>
				<li><a href="index.php?page=argomenti" class="clear">Argomenti</a></li>
				<li><a href="index.php?page=utenti" class="clear">Utenti</a></li>
			</ul>
		</li>
		<li><a href="index.php?page=domande" class="clear">Domande</a></li>
		<?php } ?>
		<li class="right"><a href="index.php?logout=1" class="clear last">Esci</a></li>
	</ul>
</div>
<script type="text/javascript">
//<![CDATA[
$(document).ready(function () {
	var page = getParameterByName('page');
	var section = getParameterByName('section');

	$('#menu > li > a').each(function () {
		if ($(this).attr('href').indexOf(page) > 0) {
			if (page === 'archivio') {
				if ($(this).attr('href').indexOf(section) > 0) {
					$(this).addClass('active');
				}
			} else {
				$(this).addClass('active');
			}
		}
	});
});
//]]>
</script>