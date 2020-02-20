<?php

class mod_bulispielplanInstallerScript
{
  /**
   * Constructor
   *
   * @param   JAdapterInstance  $adapter  The object responsible for running this script
   */
  public function __construct(JAdapterInstance $adapter)
  {
  }
  
  /**
   * Called before any type of action
   *
   * @param   string  $route  Which action is happening (install|uninstall|discover_install|update)
   * @param   JAdapterInstance  $adapter  The object responsible for running this script
   *
   * @return  boolean  True on success
   */
  public function preflight($route, JAdapterInstance $adapter)
  {
  }
  
  /**
   * Called after any type of action
   *
   * @param   string  $route  Which action is happening (install|uninstall|discover_install|update)
   * @param   JAdapterInstance  $adapter  The object responsible for running this script
   *
   * @return  boolean  True on success
   */
  public function postflight($route, JAdapterInstance $adapter)
  {
  }
  
  /**
   * Called on installation
   *
   * @param   JAdapterInstance  $adapter  The object responsible for running this script
   *
   * @return  boolean  True on success
   */
  public function install(JAdapterInstance $adapter)
  {
    $this->setupDatabase();
  }
  
  /**
   * Called on update
   *
   * @param   JAdapterInstance  $adapter  The object responsible for running this script
   *
   * @return  boolean  True on success
   */
  public function update(JAdapterInstance $adapter)
  {
    $db = JFactory::getDbo();
    $query = 'DROP TABLE '.$db->quoteName('#__bulispielplan');
    
    $db->setQuery($query);
    $db->query();
    
    $this->setupDatabase();
  }
  
  /**
   * Called on uninstallation
   *
   * @param   JAdapterInstance  $adapter  The object responsible for running this script
   */
  public function uninstall(JAdapterInstance $adapter)
  {
    $db = JFactory::getDbo();
    $query = 'DROP TABLE '.$db->quoteName('#__bulispielplan');
    
    $db->setQuery($query);
    $db->query();
  }
  
  private function setupDatabase()
  {
    $db = JFactory::getDbo();
    $query = 'CREATE TABLE IF NOT EXISTS '.$db->quoteName('#__bulispielplan').' (ID INT NOT NULL AUTO_INCREMENT PRIMARY KEY, liga VARCHAR(7), bezeichnung_webservice VARCHAR(100), bezeichnung_kurz VARCHAR(100), bezeichnung_mittel VARCHAR(100), dateiname_logo VARCHAR(100))';
    
    $db->setQuery($query);
    $db->query();
    
    $query = 'TRUNCATE TABLE '.$db->quoteName('#__bulispielplan');
    $db->setQuery($query);
    $db->query();
    
    $query = "INSERT INTO ".$db->quoteName('#__bulispielplan')." VALUES
               (1, 'bl1', 'VfL Wolfsburg', 'WOL', 'Wolfsburg', 'wolfsburg.png'), 
               (2, 'bl1', 'FC Schalke 04', 'S04', 'Schalke', 'schalke.png'),
               (3, 'bl1', 'TSG 1899 Hoffenheim', 'HOF', 'Hoffenheim', 'hoffenheim.png'), 
               (4, 'bl1', 'Werder Bremen', 'BRE', 'Bremen', 'bremen.png'),
               (5, 'bl1', 'Borussia Mönchengladbach', 'BMG', 'M''Gladbach', 'gladbach.png'), 
               (6, 'bl1', 'Eintracht Frankfurt', 'FRA', 'Frankfurt', 'frankfurt.png'), 
               (7, 'bl1', '1. FSV Mainz 05', 'MAI', 'Mainz', 'mainz.png'),
               (8, 'bl1', 'SC Freiburg', 'FRE', 'Freiburg', 'freiburg.png'), 
               (9, 'bl2', 'Hamburger SV', 'HSV', 'Hamburg', 'hamburg.png'), 
               (10, 'bl2', 'Hannover 96', 'HAN', 'Hannover', 'hannover.png'), 
               (11, 'bl1', 'Borussia Dortmund', 'BVB', 'Dortmund', 'dortmund.png'), 
               (12, 'bl2', 'VfB Stuttgart', 'STU', 'Stuttgart', 'stuttgart.png'), 
               (13, 'bl1', 'FC Augsburg', 'AUG', 'Augsburg', 'augsburg.png'),
               (14, 'bl1', 'Hertha BSC', 'BSC', 'Hertha', 'hertha.png'), 
               (15, 'bl1', '1. FC Köln', 'KLN', 'Köln', 'koeln.png'),
			   (16, 'bl1', 'RB Leipzig', 'LPZ', 'Leipzig', 'leipzig.png'), 
			   (17, 'bl1', 'Fortuna Düsseldorf', 'DÜS', 'Düsseldorf', 'duesseldorf.png'), 
               (18, 'bl2', 'FC St. Pauli', 'STP', 'St. Pauli', 'pauli.png'),
               (19, 'bl2', 'VfL Bochum', 'BOC', 'Bochum', 'bochum.png'), 
               (20, 'bl2', 'SpVgg Greuther Fürth', 'FÜR', 'Fürth', 'fuerth.png'),
               (21, 'bl2', '1. FC Heidenheim 1846', 'HEI', 'Heidenheim', 'heidenheim.png'), 
               (22, 'bl2', '1. FC Nürnberg', 'NÜR', 'Nürnberg', 'nuernberg.png'), 
               (23, 'bl2', 'SV Darmstadt 98', 'DAR', 'Darmstadt', 'darmstadt.png'), 
               (24, 'bl2', 'SV Sandhausen', 'SAN', 'Sandhausen', 'sandhausen.png'),
               (25, 'bl1', '1. FC Union Berlin', 'BER', 'Berlin', 'berlin.png'),
               (26, 'bl2', 'Arminia Bielefeld', 'BIE', 'Bielefeld', 'bielefeld.png'),
      		   (27, 'bl2', 'SG Dynamo Dresden', 'DRE', 'Dresden', 'dresden.png'),
      		   (28, 'bl2', 'Erzgebirge Aue', 'AUE', 'Aue', 'aue.png'),
      		   (29, 'bl2', 'Holstein Kiel', 'KIE', 'Kiel', 'kiel.png'),
      		   (30, 'bl2', 'Jahn Regensburg', 'REG', 'Regensburg', 'regensburg.png'),
      		   (31, 'bl1', 'FC Bayern', 'FCB', 'Bayern', 'bayern.png'),
      		   (32, 'bl1', 'Bayer Leverkusen', 'LEV', 'Leverkusen', 'leverkusen.png'),
               (33, 'bl1', 'SC Paderborn 07', 'PAD', 'Paderborn', 'paderborn.png'),
			   (34, 'bl2', 'VfL Osnabrück', 'OSN', 'Osnabrück', 'osnabrueck.png'),
			   (35, 'bl2', 'SV Wehen Wiesbaden', 'WIS', 'Wiesbaden', 'wiesbaden.png'),
			   (36, 'bl2', 'Karlsruher SC', 'KSC', 'Karlsruhe', 'karlsruhe.png');
			   ";
  
    $db->setQuery($query);
    $db->query();
	
    foreach (glob(JPATH_BASE."/../modules/mod_bulispielplan/cache_*.txt") as $cachefile) {
      if (is_readable($cachefile)) {
        unlink($cachefile);
      }
    }
  }
}
