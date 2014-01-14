<?php
/**
 * DokuWiki plugin for Piwik
 * 
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Heikki Hokkanen <hoxu@users.sf.net>
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
var pkBaseURL = (("https:" == document.location.protocol) ? "https://'. $piwik_url .'" : "http://'. $piwik_url .'");
document.write(unescape("%3Cscript src=\'" + pkBaseURL + "piwik.js\' type=\'text/javascript\'%3E%3C/script%3E"));
</script><script type="text/javascript">
try {
var piwikTracker = Piwik.getTracker(pkBaseURL + "piwik.php", '. $idsite .');
piwikTracker.trackPageView();
piwikTracker.enableLinkTracking();
} catch( err ) {}
</script><noscript><p><img src="http://'. $piwik_url .'piwik.php?idsite='. $idsite .'" style="border:0" alt=""/></p></noscript>
');
	} else {
		// Show configuration tip for admin
		if (isset($_SERVER['REMOTE_USER']) && auth_isadmin()) {
			msg('Please configure the piwik plugin');
		}
	}
}

