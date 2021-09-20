<?php
/**
 * helper.php - (c) Markus Krupp
 * Die Daten werden vom Webservice openligadb.de bereitgestellt.
 */
 
	// no direct access
	defined('_JEXEC') or die('Restricted access');
	header('Content-Type: text/html; charset=utf-8');

	require_once 'helper.php';

	try {
	  $ergebnisse = new modBulispielplanHelper($module, $params);
	  $strHTMLOutput = "\r\n<!-- Bundesliga-Spielplan 1.19 - (c) Markus Krupp - http://www.jbuli.de-->\r\n";
	  $strHTMLOutput .= '<div id="bulispielplan_' . $module->id . '"> <img id="bulispielplan_loading_' . $module->id . '" src="'.JURI::root().'modules/mod_bulispielplan/images/ajax-loader.gif"></div>';
	} catch (Exception $e) {
	  //echo $e->getMessage();
	  echo '<div align="left">'.$params->get('timeout_error').'</div>';
	}

	require JModuleHelper::getLayoutPath('mod_bulispielplan');
