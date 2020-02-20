<?php
/**
 * helper.php - (c) Markus Krupp
 * Die Daten werden vom Webservice openligadb.de bereitgestellt.
 *
 * Historie:
 *
 * 1.00 - Erste Veröffentlichung
 * 1.1  - Dropdown mit Vereinslogos
 * 1.2  - Bugfix DFB Pokal
 * 1.3  - Scrollen zum aktuellen Spieltag - 5
 * 1.4  - Saison 2016
 * 1.5  - CL 1617 hinzugefügt
 * 1.6  - Code optimiert
 * 1.7  - Ungewollten Output von anderen Plugins wie GoogleAnalytics oder PHP Meldungen im Ajax Response ignorieren
 * 1.8  - Domain geändert
 * 1.9  - Umgestellt auf HTTPS und CURL
 * 1.10 - feste CSS Breite für Bilder, letztes Spiel als current setzen nach Saisonende
 * 1.11 - Saison 2017/2018
 * 1.12 - CSS Fixes
 * 1.13 - Neue Bezeichnungen für Bayern und Leverkusen, Cache-Datei löschen bei Update
 * 1.14 - Saison 2018/2019
 * 1.17 - Saison 2019/2020
 */
 
	// no direct access
	defined('_JEXEC') or die('Restricted access');
	header('Content-Type: text/html; charset=utf-8');

	require_once 'helper.php';

	try {
	  $ergebnisse = new modBulispielplanHelper($module, $params);
	  $strHTMLOutput = "\r\n<!-- Bundesliga-Spielplan 1.17 - (c) Markus Krupp - http://www.jbuli.de-->\r\n";
	  $strHTMLOutput .= '<div id="bulispielplan_' . $module->id . '"> <img id="bulispielplan_loading_' . $module->id . '" src="'.JURI::root().'modules/mod_bulispielplan/images/ajax-loader.gif"></div>';
	} catch (Exception $e) {
	  //echo $e->getMessage();
	  echo '<div align="left">'.$params->get('timeout_error').'</div>';
	}

	require JModuleHelper::getLayoutPath('mod_bulispielplan');
