<!-- $Id: ranks_edit_body.tpl,v 1.1 2004/08/30 21:30:07 dmaj007 Exp $ -->
<script language="javascript" type="text/javascript">
<!--
function update_rank(newimage)
{
   document.rank_image.src = newimage;
}
//-->
</script>
<h1>{L_RANKS_TITLE}</h1>

<p>{L_RANKS_TEXT}</p>

<form action="{S_RANK_ACTION}" method="post"><table class="forumline" cellpadding="4" cellspacing="1" border="0" align="center">
    <tr>
        <th class="thTop" colspan="2">{L_RANKS_TITLE}</th>
    </tr>
    <tr>
        <td class="row1" width="38%"><span class="gen">{L_RANK_TITLE}:</span></td>
        <td class="row2">
            <table cellpadding="2" cellspacing="1" border="0" width="100%">
            <tr>
                <td align="right" nowrap="nowrap"><span class="gen"><b>{L_RANK_DEFAULT}:</b></td>
                <td width="100%"><input class="post" type="text" name="title_default" size="35" maxlength="85" value="{RANK_DEFAULT}" /></span></td>
            </tr>
            <tr>
                <td align="right" nowrap="nowrap"><span class="gen"><b>{L_RANK_MALE}:</b></td>
                <td width="100%"><input class="post" type="text" name="title_male" size="35" maxlength="85" value="{RANK_MALE}" /></span></td>
            </tr>
            <tr>
                <td align="right" nowrap="nowrap"><span class="gen"><b>{L_RANK_FEMALE}:</b></td>
                <td width="100%"><input class="post" type="text" name="title_female" size="35" maxlength="85" value="{RANK_FEMALE}" /></span></td>
            </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td class="row1"><span class="gen">{L_RANK_SPECIAL}</span></td>
        <td class="row2"><input type="radio" name="special_rank" value="1" {SPECIAL_RANK} />{L_YES} &nbsp;&nbsp;<input type="radio" name="special_rank" value="0" {NOT_SPECIAL_RANK} /> {L_NO}</td>
    </tr>
    <tr>
        <td class="row1" width="38%"><span class="gen">{L_RANK_MINIMUM}:</span></td>
        <td class="row2"><input class="post" type="text" name="min_posts" size="5" maxlength="10" value="{MINIMUM}" /></td>
    </tr>
    <tr>
        <td class="row1" width="38%"><span class="gen">{L_RANK_IMAGE}:</span><br /><span class="gensmall">{L_RANK_IMAGE_EXPLAIN}</span></td>
        <td class="row2"><select name="rank_image" onchange="update_rank(this.options[selectedIndex].value);">{RANK_LIST}</select>&nbsp;{IMAGE_DISPLAY}&nbsp;</td>
    </tr>
    <tr>
        <td class="catBottom" colspan="2" align="center"><input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td>
    </tr>
</table>
{S_HIDDEN_FIELDS}</form>
