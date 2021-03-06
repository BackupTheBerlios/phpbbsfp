<?php
//----------------------------------------------------------------------------------------[English]-
//
// $Id: lang_extend_lang_extend.php,v 1.1 2004/08/30 21:30:06 dmaj007 Exp $
//
// FILENAME  : lang_extend_lang_extend.php
// STARTED   : Tue Jan 1, 2004
// COPYRIGHT : � 2003, 2004 Project Minerva Team
//           : � 2001, 2003 The phpBB Group
//           : � 2003       Ptirhiik
// WWW       : http://www.project-minerva.org/
// LICENCE   : GPL v2.0 [ see /docs/COPYING ]
//
//--------------------------------------------------------------------------------------------------

if ( !defined('IN_PHPBB') )
{
    die("Hacking attempt");
}

// admin part
if ( $lang_extend_admin )
{
    $lang['Lang_extend_lang_extend'] = 'Extension for languages packs';
    $lang['Lang_extend__custom'] = 'Custom language pack';
    $lang['Lang_extend__phpBB'] = 'Minerva language pack';

    $lang['Languages'] = 'Languages';
    $lang['Lang_management'] = 'Management';
    $lang['Lang_extend'] = 'Lang extend management';
    $lang['Lang_extend_explain'] = 'Here you can add or modify languages key entries';
    $lang['Lang_extend_pack'] = 'Language Pack';
    $lang['Lang_extend_pack_explain'] = 'This is the name of the pack, usualy the name of the MOD refering to';

    $lang['Lang_extend_entry'] = 'Language key entry';
    $lang['Lang_extend_entries'] = 'Language key entries';
    $lang['Lang_extend_level_admin'] = 'Admin';
    $lang['Lang_extend_level_normal'] = 'Normal';

    $lang['Lang_extend_add_entry'] = 'Add a new lang key entry';

    $lang['Lang_extend_key_main'] = 'Language main key entry';
    $lang['Lang_extend_key_main_explain'] = 'This is the main key entry, usualy the only one';
    $lang['Lang_extend_key_sub'] = 'Secondary key entry';
    $lang['Lang_extend_key_sub_explain'] = 'This second level key entry is usualy not used';
    $lang['Lang_extend_level'] = 'Level of the lang key entry';
    $lang['Lang_extend_level_explain'] = 'Admin level can only be used in the admin configuration panel. Normal level can be used everywhere.';

    $lang['Lang_extend_missing_value'] = 'You have to provide at least the English value';
    $lang['Lang_extend_key_missing'] = 'Main entry key is missing';
    $lang['Lang_extend_duplicate_entry'] = 'This entry already exists (see pack %)';

    $lang['Lang_extend_update_done'] = 'The entry has been successfully updated.<br /><br />Click %sHere%s to return to the entry.<br /><br />Click %sHere%s to return to entries list';
    $lang['Lang_extend_delete_done'] = 'The entry has been successfully deleted.<br />Note that only customized key entries are deleted, not the basic key entries if exist.<br /><br />Click %sHere%s to return to entries list';

    $lang['Lang_extend_search'] = 'Search in language key entries';
    $lang['Lang_extend_search_words'] = 'Words to find';
    $lang['Lang_extend_search_words_explain'] = 'Separate words with a space';
    $lang['Lang_extend_search_all'] = 'All words';
    $lang['Lang_extend_search_one'] = 'One of those';
    $lang['Lang_extend_search_in'] = 'Search in';
    $lang['Lang_extend_search_in_explain'] = 'Precise where to search';
    $lang['Lang_extend_search_in_key'] = 'keys';
    $lang['Lang_extend_search_in_value'] = 'values';
    $lang['Lang_extend_search_in_both'] = 'both';
    $lang['Lang_extend_search_all_lang'] = 'All languages installed';

    $lang['Lang_extend_search_no_words'] = 'No words to search provided.<br /><br />Click %sHere%s to return to the pack list.';
    $lang['Lang_extend_search_results'] = 'Search results';
    $lang['Lang_extend_value'] = 'Value';
    $lang['Lang_extend_level_leg'] = 'Level';

    $lang['Lang_extend_added_modified'] = '*';
    $lang['Lang_extend_modified'] = 'Modified';
    $lang['Lang_extend_added'] = 'Added';
}

?>