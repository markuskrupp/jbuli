<?php
/**
 * mod_buliergebnisse.php - (c) Markus Krupp
 * Die Daten werden vom Webservice openligadb.de bereitgestellt
 *
 * Historie:
 *
 * 1.00 - Erste Veröffentlichung
 * 1.01 - Installations Script ausgelagert und auf Joomla 3 migriert
 * 1.02 - Saison 2014 und Liga 2
 * 1.03 - AJAX Spieltags-Navigation
 * 1.04 - Cache eingebaut
 * 1.05 - Fehlerbehandlung optimiert
 * 1.1  - Umstellung auf JSON API von openligadb, einfacheres AJAX Handling und Updater
 * 1.2  - Optionale Kompaktansicht, Hervorheben eines Vereins, aktueller Spieltag fett im Spieltags-Dropdown
 * 1.3  - Tooltips zur Anzeige der Torschützen
 * 1.4  - Saison 2015/2016 als Standard
 * 1.5  - Keine PHP Notices mehr, AJAX Endpoint static und Versionsnummer im HTML Kommentar
 * 1.6  - Bugfix falsches Ergebnis während das Spiel läuft beim Stande von 0:0
 * 1.7  - Flag "Immer Lange Vereinsnamen", -1 für vorherigen Spieltag, Modulklassensuffix, Internationale Ligen, Spieltags-Dropdown auch anzeigen wenn keine Daten
 * 1.8  - Saison 2016
 * 1.9  - Saison 2016 Internationale Ligen
 * 1.10 - CL1617
 * 1.11 - Ungewollten Output von anderen Plugins wie GoogleAnalytics oder PHP Meldungen im Ajax Response ignorieren
 * 1.12 - Domain geändert
 * 1.13 - Umgestellt auf HTTPS und CURL
 * 1.14 - feste CSS Breite für Bilder
 * 1.15 - Saison 2017/2018
 * 1.16 - Neue Bezeichnungen für Bayern und Leverkusen, Cache-Datei löschen bei Update
 * 1.17 - Saison 2018/2019
 * 1.20 - Saison 2019/2020
 */
  
	// no direct access
	defined('_JEXEC') or die('Restricted access');
	header('Content-Type: text/html; charset=utf-8');

	require_once 'helper.php';

	try {
	  $ergebnisse = new modBuliergebnisseHelper($module);
	  $strHTMLOutput = "\r\n<!-- Bundesliga-Ergebnisse 1.20 - (c) Markus Krupp - http://www.jbuli.de-->\r\n";
	  $strHTMLOutput .= "<div id='spielplan_" . $module->id . "'> <img id='buliergebnisse_loading_" . $module->id . "' src='".JURI::root()."modules/mod_buliergebnisse/images/ajax-loader.gif'></div>\r\n";
	} catch (Exception $e) {
	  //echo $e->getMessage();
	  echo '<div align="left">'.$params->get('timeout_error').'</div>';
	}

	require JModuleHelper::getLayoutPath('mod_buliergebnisse');
