<!-- $Id: overall_footer.tpl,v 1.1 2004/08/30 21:32:17 dmaj007 Exp $ -->
                        <!-- IF S_BOTTOM_BLOCKS -->
                            {BOTTOM_BLOCKS}
                        <!-- ENDIF -->
                    </td>

                    <!-- IF S_RIGHT_BLOCKS -->
                        {RIGHT_BLOCKS}
                    <!-- ENDIF -->

                </tr>
            </table>

            <div align="center">
              <span class="copyright"><br />{ADMIN_LINK}<br />
                Powered by <a href="http://www.project-minerva.org/" target="_minerva" class="copyright">Minerva</a> {MINERVA_VERSION} &copy; 2003-2004 Project Minerva. Minerva is derived from phpBB2 &copy; 2001-2004 <a href="http://www.phpbb.com/" target="_phpbb" class="copyright">The phpBB Group</a>.
                {S_TIMEZONE}.
				<!-- IF TRANSLATION_INFO -->
					<br />{TRANSLATION_INFO} 
				<!-- ENDIF -->
				<!-- IF MODULE_INFO -->
					<br />{MODULE_INFO}
				<!-- ENDIF -->
				<!-- IF LEGAL_INFO -->
					<br />{LEGAL_INFO}
				<!-- ENDIF -->
			  </span>
            </div>
        </td>
    </tr>
</table>

<a name="bot" id="bot"></a>

<!-- IF PAGE_GENERATION != '' -->
<br /><div style="font-family: Verdana; font-size: 10px; color: #000000; letter-spacing: -1px" align="center">{PAGE_GENERATION}</div>
<!-- ENDIF -->
</body>
</html>