<form method="post" action="{S_POLL_ACTION}">
<table border="0" cellpadding="0" cellspacing="1" width="100%">
<!-- IF NO_POLLS -->
<!-- INCLUDE poll_empty_block.tpl -->

<!-- ELSEIF POLL_RESULT_INCLUDE -->
<!-- INCLUDE poll_result_block.tpl -->

<!-- ELSE -->
<!-- INCLUDE poll_ballot_block.tpl -->

<!-- ENDIF -->
</table>
</form>
