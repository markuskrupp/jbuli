<?php

class modBulispielplanHelper
{
    /**
     * Constructor
     */
    public function __construct($module, $params)
    {
        // Load Bootstrap and JQuery
        JHtml::_('bootstrap.framework');
        JHtml::_('jquery.ui');
        JHtml::script('modules/mod_bulispielplan/jquery.selectBoxIt.min.js');
        JHtml::stylesheet('modules/mod_bulispielplan/jquery.selectBoxIt.css');

        $app = JFactory::getApplication();
        $document = JFactory::getDocument();

        $style = '.selectboxit-container .selectboxit-options {
                 max-height: ' . ($params->get('hoehe') -20) . 'px;
              }
              .selectboxit-container .selectboxit {
                width: ' . ($params->get('breite') -20) .'px;
              }
              ';
        $document->addStyleDeclaration($style);

        $document->addScriptDeclaration('
      jQuery(document).ready(function() {
        change_verein_' . $module->id . '();
        jQuery(document).on("change", "#verein_' . $module->id . '", change_verein_' . $module->id . ');
      });
        
      function change_verein_' . $module->id . '() {
        jQuery("#bulispielplan_loading_' . $module->id . '").show();
        jQuery.post( "' . JURI::base() . 'index.php",
            {
              option: "com_ajax",
              module: "bulispielplan",
              Itemid: "' . $app->getMenu()->getActive()->id . '",
              method: "getSpielplan",
              format: "json",
              titel: "' . $module->title . '",
              verein: encodeURI(jQuery("#verein_' . $module->id . ' option:selected").text()),
            },
            function(data){
              jQuery("#bulispielplan_loading_' . $module->id . '").hide();
              if (data.success == false) {
                jQuery("#bulispielplan_' . $module->id . '").html(data.message);
              } else {
                jQuery("#bulispielplan_' . $module->id . '").html(data.data);
                jQuery("#verein_' . $module->id . '").selectBoxIt({autoWidth: false});
                jQuery(".hasTooltip").tooltip({html: "true"});
				
				        var divpos = jQuery("#bulispielplan_' . $module->id . '").children("div").offset().top;
				        var elementpos = jQuery("#' . $module->id . '_current").prevAll(":eq(4)").offset().top;
				        jQuery("#bulispielplan_' . $module->id . '").children("div").scrollTop(elementpos-divpos);
              }
            }
        ).fail(function(xhr) {
		  try {
			// Ungewollten Output von anderen Plugins wie GoogleAnalytics oder PHP Meldungen wegschneiden
			data = jQuery.parseJSON(xhr.responseText.substring(xhr.responseText.indexOf("success")-2));
		  }
		  catch (e) {
			alert("Fehlerhafter JSON Response - Doku pruefen!");
		  };
          jQuery("#bulispielplan_loading_' . $module->id . '").hide();
          if (data.success == false) {
            jQuery("#bulispielplan_' . $module->id . '").html(data.message);
          } else {
            jQuery("#bulispielplan_' . $module->id . '").html(data.data);
            jQuery("#verein_' . $module->id . '").selectBoxIt({autoWidth: false});
            jQuery(".hasTooltip").tooltip({html: "true"});
				
				    var divpos = jQuery("#bulispielplan_' . $module->id . '").children("div").offset().top;
				    var elementpos = jQuery("#' . $module->id . '_current").prevAll(":eq(4)").offset().top;
				    jQuery("#bulispielplan_' . $module->id . '").children("div").scrollTop(elementpos-divpos);
          }
        });
      };
    ');
    }

    /**
     * fetch data from api using curl or file_get_contents
     */
    public static function fetchdata($url, $timeout)
    {
        if (function_exists('curl_version')) {
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
            $content = curl_exec($curl);
            curl_close($curl);

            return $content;
        } elseif (ini_get('allow_url_fopen')) {
            $context = stream_context_create([
        'http' => [ 'timeout' => $timeout ]
      ]);

            return file_get_contents($url, 0, $context);
        } else {
            return false;
        }
    }

    /**
     * AJAX Endpoint
     */
    public static function getSpielplanAjax()
    {
        $jinput = JFactory::getApplication()->input;
        $module = JModuleHelper::getModule('bulispielplan', $jinput->get('titel', 'default_value', 'filter'));
        $db = JFactory::getDbo();

        $jparams = new JRegistry();
        $jparams->loadString($module->params);

        $context = stream_context_create([
      'http' => [ 'timeout' => $jparams->get('timeout') ]
    ]);

        // Liga ermitteln
        $query = 'SELECT '.$db->quoteName('liga').' FROM '.$db->quoteName('#__bulispielplan') . ' WHERE bezeichnung_webservice = ' . $db->quote($jparams->get('meinVerein'));
        $db->setQuery($query);
        $liga = $db->loadResult();

        // Teams aus der Joomla Tabelle holen
        $query = 'SELECT '.$db->quoteName('bezeichnung_webservice').', '.$db->quoteName('bezeichnung_kurz').', '.$db->quoteName('bezeichnung_mittel').', '.$db->quoteName('dateiname_logo').' FROM '.$db->quoteName('#__bulispielplan') . ' WHERE liga = ' . $db->quote($liga) . ' ORDER BY bezeichnung_mittel';
        $db->setQuery($query);
        $teams = $db->loadAssocList('bezeichnung_webservice');

        // Start HTML OUTPUT
        $breite = $jparams->get('breite');
        $table = "\r\n<table border='0' style='width:" . ($breite - 20) . "px;'>\r\n";

        // Verein Dropdown
        $table .= "<tr><td align='left' valign='middle'><nobr><select id='verein_" . $module->id . "'>";
        $verein = '';

        foreach ($teams as $team) {
            if ($team['bezeichnung_webservice'] == urldecode($jinput->get('verein', 'default_value', 'filter'))) {
                $table .= '<option data-text="<strong style=\'font-weight:bold;\'>' . $team['bezeichnung_mittel'] . '</strong>" data-iconurl="'.JURI::root().'modules/mod_bulispielplan/images/' . $team['dateiname_logo'] . '" selected="selected">' . $team['bezeichnung_webservice'] . '</option>';
                $verein = urldecode($jinput->get('verein', 'default_value', 'filter'));
            } elseif ($team['bezeichnung_webservice'] == $jparams->get('meinVerein') && $jinput->get('verein', 'default_value', 'filter') == '') {
                $table .= '<option data-text="<strong style=\'font-weight:bold;\'>' . $team['bezeichnung_mittel'] . '</strong>" data-iconurl="'.JURI::root().'modules/mod_bulispielplan/images/' . $team['dateiname_logo'] . '" selected="selected">' . $team['bezeichnung_webservice'] . '</option>';
                $verein = $jparams->get('meinVerein');
            } elseif ($team['bezeichnung_webservice'] == $jparams->get('meinVerein')) {
                $table .= '<option data-text="<strong style=\'font-weight:bold;\'>' . $team['bezeichnung_mittel'] . '</strong>" data-iconurl="'.JURI::root().'modules/mod_bulispielplan/images/' . $team['dateiname_logo'] . '">' . $team['bezeichnung_webservice'] . '</option>';
            } else {
                $table .= '<option data-text="' . $team['bezeichnung_mittel'] . '" data-iconurl="'.JURI::root().'modules/mod_bulispielplan/images/' . $team['dateiname_logo'] . '">' . $team['bezeichnung_webservice'] . '</option>';
            }
        }

        $table .= "</select>&nbsp;&nbsp;&nbsp;<img id='bulispielplan_loading_" . $module->id . "' src='".JURI::root()."modules/mod_bulispielplan/images/ajax-loader.gif' style='display:none;'></nobr></td></tr></table>";
        $table .= "<div style='height:" . $jparams->get('hoehe') . "px; width:" . ($breite + 20) . "px; overflow-y:auto; overflow-x:hidden; padding-right:5px; margin-top:20px;'>";
        $table .= "<table border='0' style='width:" . ($breite) . "px;'>\r\n";

        $ligen = [$liga, 'dfb' . $jparams->get('season')];
        $partien = [];

        foreach ($ligen as $liga) {
            $cache = '';
            $cachefile = JPATH_BASE . '/modules/mod_bulispielplan/cache_' . $liga . $jparams->get('season') . '.txt';
            if (is_readable($cachefile)) {
                $cache = file_get_contents($cachefile);
                $paarungen = unserialize($cache);
            }

            // Daten neu holen wenn Refresh-Intervall erreicht
            if ($cache == '' || $jparams->get('lastupdate') == '' || ($jparams->get('lastupdate') + ($jparams->get('refresh') * 60) < time())) {
                $paarungenjson = self::fetchdata('https://www.openligadb.de/api/getmatchdata/' . $liga . '/' . $jparams->get('season'), $jparams->get('timeout'));

                if ($paarungenjson != false && stristr($paarungenjson, 'Maximale Abfrageanzahl von 1000 Abfragen pro Tag erreicht!') == false && stristr($paarungenjson, 'An error has occurred') == false) {
                    $paarungen = json_decode($paarungenjson);
                    file_put_contents($cachefile, serialize($paarungen));

                    // set last update param
                    $jparams->set('lastupdate_' . $liga, time());
                    $module->params = $jparams->toString();
                    $jtable = JTable::getInstance('module');
                    $jtable->save((array)$module);
                } else {
                    if ($cache == '') {
                        // Keine Daten im Cache und Webservice nicht erreichbar
                        throw new Exception($jparams->get('timeout_error'));
                    } else {
                        $paarungen = unserialize($cache);
                    }
                }
            }

            foreach ($paarungen as $partie) {
                $partie->wettbewerb = $liga;
            }

            $partien = array_merge($partien, $paarungen);
        }

        usort($partien, function ($a, $b) {
            return strcmp($a->MatchDateTime, $b->MatchDateTime);
        });

        $anzahl_partien = 0;
        foreach ($partien as $partie) {
            if ($partie->Team1->TeamName == $verein || $partie->Team2->TeamName == $verein) {
                $anzahl_partien++;
            }
        }

        // Output Spielplan
        $i = 0;
        $c = 0;
        foreach ($partien as $partie) {
            if ($partie->Team1->TeamName == $verein || $partie->Team2->TeamName == $verein) {
                $c++;
                $tootip_text = '';
                $goals = '';
                $ergebnisse = '<td>';
                $alle_ergebnisse = $partie->MatchResults;

                if (! is_array($alle_ergebnisse) || count($alle_ergebnisse) == 0) {
                    $tootip_text .= '&nbsp;-:-';
                    if ($id != 'current' && $hat_ergebnisse) {
                        $id = 'current';
                    } else {
                        $id = '';
                    }

                    $hat_ergebnisse = false;
                } else {
                    if (!$partie->MatchIsFinished && $alle_ergebnisse[0] instanceof stdClass) {
                        $tootip_text .= '<font color="red">';
                    }

                    $ergebnisse .= '<nobr>&nbsp;';
                    $id = '';
                    $hat_ergebnisse = true;

                    // Endergebnis ermitteln
                    foreach ($alle_ergebnisse as $ergebnis) {
                        if ($ergebnis->ResultName == 'Endergebnis') {
                            if ($partie->Team1->TeamName == $verein) {
                                $tootip_text .= $ergebnis->PointsTeam1.":".$ergebnis->PointsTeam2;
                            } else {
                                $tootip_text .= $ergebnis->PointsTeam2.":".$ergebnis->PointsTeam1;
                            }

                            break;
                        }
                    }

                    foreach ($partie->Goals as $goal) {
                        if ($goal->GoalGetterName) {
                            if ($goal->MatchMinute) {
                                if ($partie->Team1->TeamName == $verein) {
                                    $goals .= '<b>' . $goal->ScoreTeam1 . ':' . $goal->ScoreTeam2 . '</b>&nbsp;&nbsp;' . $goal->GoalGetterName . ' (' . $goal->MatchMinute . '.)<br>';
                                } else {
                                    $goals .= '<b>' . $goal->ScoreTeam2 . ':' . $goal->ScoreTeam1 . '</b>&nbsp;&nbsp;' . $goal->GoalGetterName . ' (' . $goal->MatchMinute . '.)<br>';
                                }
                            } else {
                                if ($partie->Team1->TeamName == $verein) {
                                    $goals .= '<b>' . $goal->ScoreTeam1 . ':' . $goal->ScoreTeam2 . '</b>&nbsp;&nbsp;' . $goal->GoalGetterName . '<br>';
                                } else {
                                    $goals .= '<b>' . $goal->ScoreTeam2 . ':' . $goal->ScoreTeam1 . '</b>&nbsp;&nbsp;' . $goal->GoalGetterName . '<br>';
                                }
                            }
                        }
                    }
                }

                if (isset($partie->matchIsFinished)) {
                    if (!$partie->matchIsFinished && $alle_ergebnisse[0] instanceof stdClass) {
                        $tootip_text .= "</font>";
                    }
                }

                $tootip_text .= "</nobr>";

                if ($goals <> '') {
                    $ergebnisse .= JHtml::_('tooltip', $goals, 'Tore', '', $tootip_text);
                } else {
                    $ergebnisse .= $tootip_text;
                }

                $ergebnisse .= "</td>\r\n";
            }

            $tage = ["So.", "Mo.", "Di.", "Mi.", "Do.", "Fr.", "Sa."];

            if ($partie->Team1->TeamName == $verein || $partie->Team2->TeamName == $verein) {
                if ($partie->wettbewerb == $ligen[0]) {
                    $anzeigename = 'Bundesliga';
                    $i++;
                    $kurz = $i;
                } elseif ($partie->wettbewerb == $ligen[1]) {
                    $kurz = 'PK';
                    $anzeigename = 'DFB Pokal';
                    $bild = 'pokal.png';
                } elseif ($partie->wettbewerb == $ligen[2]) {
                    $kurz = 'CL';
                    $anzeigename = 'Champions League';
                    $bild = 'cl.png';
                }

                if ($partie->Team1->TeamName == $verein) {
                    $wo = 'H';
                    $anzeige = $partie->Team2->TeamName;
                    if ($partie->wettbewerb == $ligen[0]) {
                        $anzeige = $teams[$partie->Team2->TeamName]['bezeichnung_mittel'];
                        $bild = $teams[$partie->Team2->TeamName]['dateiname_logo'];
                    }
                } else {
                    $wo = 'A';
                    $anzeige = $partie->Team1->TeamName;
                    if ($partie->wettbewerb == $ligen[0]) {
                        $anzeige = $teams[$partie->Team1->TeamName]['bezeichnung_mittel'];
                        $bild = $teams[$partie->Team1->TeamName]['dateiname_logo'];
                    }
                }

                // Workaround wenn die Saison vorbei ist das letzte Spiel als current setzen
                if ($id != 'current' && $c == $anzahl_partien && $hat_ergebnisse) {
                    $id = 'current';
                }
                if ($anzeigename != 'Bundesliga') {
                    $tooltip = $anzeige;
                } else {
                    $tooltip = '';
                }

                $table .= '<tr id="' . $module->id . '_' . $id . '"><td style="text-align:right; padding-right: 5px;">' . $kurz . '</td>
        <td> ' . JHtml::_('tooltip', '<nobr>' . $tage[date("w", strtotime($partie->MatchDateTime))].' '.date('d.m.Y H:i', strtotime($partie->MatchDateTime)) . ' Uhr </nobr><br>' . (is_object($partie->Location) ? $partie->Location->LocationStadium : ''), $anzeigename, '', date('d.m.', strtotime($partie->MatchDateTime))) .  '</td>
        <td><img style="width:20px; height:20px;" border="0" title="' . $anzeige . '" alt="' . $anzeige . '" src="'.JURI::root().'modules/mod_bulispielplan/images/' . $bild . '"></td>
        <td><div title="' . $tooltip . '" style="cursor: default; text-overflow: ellipsis; overflow: hidden; white-space: nowrap; width: ' . ($breite-130) . 'px">' . $anzeige . '</div></td>
        <td>' . $wo . '</td><td>' . $ergebnisse . '</tr>';
            }
        }

        $table .= "</table></div>";

        return $table;
    }
}
