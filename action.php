<?php
/**
 * DokuWiki plugin for Piwik
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Heikki Hokkanen <hoxu@users.sf.net>
 */

if(!defined('DOKU_INC')) die();
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once DOKU_PLUGIN.'action.php';
require_once DOKU_PLUGIN.'piwik/code.php';

class action_plugin_piwik extends DokuWiki_Action_Plugin {
	function register(&$controller) {
		$controller->register_hook('TPL_METAHEADER_OUTPUT', 'BEFORE', $this, '_hook_header');
	}

	function _hook_header(&$event, $param) {
		$data = piwik_code_new();
		$event->data['script'][] = array(
			'type' => 'text/javascript',
			'charset' => 'utf-8',
			'_data' => $data,
		);
	}
}
