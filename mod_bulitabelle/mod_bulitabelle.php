<?php
/**
 * mod_bulitabelle.php - (c) Markus Krupp
 * Die Daten werden vom Webservice openligadb bereitgestellt.
 */
 
  // no direct access
  defined('_JEXEC') or die('Restricted access');
  header('Content-Type: text/html; charset=utf-8');
  
  require_once 'helper.php';
  
  try {
    $tabelle = new modBulitabelleHelper($module);
    $strHTMLOutput = "\r\n<!-- Bundesliga-Tabelle 1.18 - (c) Markus Krupp - http://www.jbuli.de-->\r\n";
    $strHTMLOutput .= '<div id="bulitabelle_' . $module->id . '"> <img id="bulitabelle_loading_' . $module->id . '" src="'.JURI::root().'modules/mod_bulitabelle/images/ajax-loader.gif"></div>';
  } catch (Exception $e) {
    echo '<div align="left">Ein Fehler ist aufgetreten:<br>' . $e->getMessage() . '</div>';
  }

  require JModuleHelper::getLayoutPath('mod_bulitabelle');
