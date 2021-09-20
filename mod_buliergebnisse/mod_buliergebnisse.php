<?php
/**
 * mod_buliergebnisse.php - (c) Markus Krupp
 * Die Daten werden vom Webservice openligadb.de bereitgestellt
 */
  
	// no direct access
	defined('_JEXEC') or die('Restricted access');
	header('Content-Type: text/html; charset=utf-8');

	require_once 'helper.php';

	try {
	  $ergebnisse = new modBuliergebnisseHelper($module);
	  $strHTMLOutput = "\r\n<!-- Bundesliga-Ergebnisse 1.22 - (c) Markus Krupp - http://www.jbuli.de-->\r\n";
	  $strHTMLOutput .= "<div id='spielplan_" . $module->id . "'> <img id='buliergebnisse_loading_" . $module->id . "' src='".JURI::root()."modules/mod_buliergebnisse/images/ajax-loader.gif'></div>\r\n";
	} catch (Exception $e) {
	  //echo $e->getMessage();
	  echo '<div align="left">'.$params->get('timeout_error').'</div>';
	}

	require JModuleHelper::getLayoutPath('mod_buliergebnisse');
