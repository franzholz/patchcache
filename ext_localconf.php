<?php
defined('TYPO3_MODE') || die('Access denied.');

$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects']['TYPO3\\CMS\\Core\\Routing\\PageRouter'] = array(
	'className' => 'Bugfix\\Patchcache\\Core\\View\\Routing\\PageRouter'
);

$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects']['TYPO3\\CMS\\Frontend\\Typolink\\PageLinkBuilder'] = array(
	'className' => 'Bugfix\\Patchcache\\Frontend\\Typolink\\PageLinkBuilder'
);


