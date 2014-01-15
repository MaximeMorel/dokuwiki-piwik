<?php
/**
 * DokuWiki plugin for Piwik
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Heikki Hokkanen <hoxu@users.sf.net> & Maxime Morel <maxime.morel69@gmail.com>
 */
require_once(DOKU_INC .'inc/auth.php');

// Old plugin version instructed people to modify tpl/default/main.php and call
// this function
function piwik_code() {
	//msg('Piwik plugin: old version required template modification, you should remove piwik call from tpl/default/main.php now.');
}

/**
 * Prints the snippet needed for Piwik.
 */
function piwik_code_new()
{
	global $conf;

	if (isset($conf['plugin']['piwik']['piwik_idsite'])) {
		// Config does not contain keys if they are default;
		// so check whether they are set & to non-default value

		// default 0, so check if it's not set or 0
		if (!isset($conf['plugin']['piwik']['count_admins']) || $conf['plugin']['piwik']['count_admins'] == 0) {
			if (isset($_SERVER['REMOTE_USER']) && auth_isadmin()) { return; }
		}

		// default 1, so check if it's set and 0
		if (isset($conf['plugin']['piwik']['count_users']) && $conf['plugin']['piwik']['count_users'] == 0) {
			if (isset($_SERVER['REMOTE_USER'])) { return; }
		}

		$idsite = $conf['plugin']['piwik']['piwik_idsite'];
		$piwik_url = $conf['plugin']['piwik']['piwik_url'];
		$piwik_url = str_replace('http://', '', $piwik_url); // Remove 'http://' if any
		$piwik_url = rtrim($piwik_url, '/') . '/'; // Make sure the URL has '/' in the end

		ptln(
'<script type="text/javascript">
  var _paq = _paq || [];
  _paq.push(["trackPageView"]);
  _paq.push(["enableLinkTracking"]);
  (function() {
    var u=(("https:" == document.location.protocol) ? "https" : "http") + "'.$piwik_url.'";
    _paq.push(["setTrackerUrl", u+"piwik.php"]);
    _paq.push(["setSiteId", "'.$idsite.'"]);
    var d=document, g=d.createElement("script"), s=d.getElementsByTagName("script")[0]; g.type="text/javascript";
    g.defer=true; g.async=true; g.src=u+"piwik.js"; s.parentNode.insertBefore(g,s);
  })();
</script>
');
	} else {
		// Show configuration tip for admin
		if (isset($_SERVER['REMOTE_USER']) && auth_isadmin()) {
			msg('Please configure the piwik plugin');
		}
	}
}

