<?php
/**
 * mod_bulitabelle.php - (c) Markus Krupp
 * Die Daten werden vom Webservice openligadb bereitgestellt.
 * 
 * Historie:
 * 
 * 1.0.0 - Erste Veröffentlichung
 * 1.0.1 - Saison 2014
 * 1.0.2 - Fehlerbehandlung optimiert
 * 1.1   - Umstellung auf JSON API von openligadb, Laden der Tabelle per AJAX, Hervorheben eines Vereins und Updater
 * 1.2   - Bugfixes
 * 1.3   - Saison 2015/2016 als Standard
 * 1.4   - Keine PHP Notices mehr, AJAX Endpoint static und Versionsnummer im HTML Kommentar
 * 1.5   - Fehler Spieltag 1, falsches Logo St. Pauli, 3 Punkte Strafe Sandhausen 2015
 * 1.6   - Saison 2016
 * 1.7   - Ungewollten Output von anderen Plugins wie GoogleAnalytics oder PHP Meldungen im Ajax Response ignorieren
 * 1.8   - Domain geändert
 * 1.9   - Umgestellt auf HTTPS und CURL
 * 1.10  - feste CSS Breite für Bilder
 * 1.11  - Saison 2017/2018
 * 1.12  - Neue Bezeichnungen für Bayern und Leverkusen, Cache-Datei löschen bei Update
 * 1.13  - Saison 2018/2019
 * 1.16  - Sasion 2019/2020
 */
 
  // no direct access
  defined( '_JEXEC' ) or die( 'Restricted access' );
  header('Content-Type: text/html; charset=utf-8'); 
  
  require_once 'helper.php';
  
  try{
	$tabelle = new modBulitabelleHelper($module);
	$strHTMLOutput = "\r\n<!-- Bundesliga-Tabelle 1.16 - (c) Markus Krupp - http://www.jbuli.de-->\r\n";
	$strHTMLOutput .= '<div id="bulitabelle_' . $module->id . '"> <img id="bulitabelle_loading_' . $module->id . '" src="'.JURI::root().'modules/mod_bulitabelle/images/ajax-loader.gif"></div>';
  }
  catch (Exception $e) {
    echo '<div align="left">Ein Fehler ist aufgetreten:<br>' . $e->getMessage() . '</div>';
  }

  require( JModuleHelper::getLayoutPath( 'mod_bulitabelle' ) );
?>