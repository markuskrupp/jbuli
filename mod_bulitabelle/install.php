<?php

class mod_bulitabelleInstallerScript
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
        $query = 'DROP TABLE '.$db->quoteName('#__bulitabelle');

        $db->setQuery($query);
        $db->query();
    }

    private function setupDatabase()
    {
        $db = JFactory::getDbo();
        $query = 'CREATE TABLE IF NOT EXISTS '.$db->quoteName('#__bulitabelle').' (ID INT NOT NULL AUTO_INCREMENT PRIMARY KEY, team VARCHAR(100), spiele INT, tore INT, gegentore INT, punkte INT, modul_id INT)';
        $db->setQuery($query);
        $db->query();

        $query = 'TRUNCATE TABLE '.$db->quoteName('#__bulitabelle');
        $db->setQuery($query);
        $db->query();

        $cachefile = JPATH_BASE."/../modules/mod_bulitabelle/cache.txt";
        if (is_readable($cachefile)) {
            unlink($cachefile);
        }
    }
}
