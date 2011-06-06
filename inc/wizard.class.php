<?php

/*
   ----------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2011 by the FusionInventory Development Team.

   http://www.fusioninventory.org/   http://forge.fusioninventory.org/
   ----------------------------------------------------------------------

   LICENSE

   This file is part of FusionInventory.

   FusionInventory is free software: you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation, either version 2 of the License, or
   any later version.

   FusionInventory is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with FusionInventory.  If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------
   Original Author of file: David DURIEUX
   Co-authors of file:
   Purpose of file:
   ----------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}


class PluginFusioninventoryWizard {

   function filAriane($ariane) {
      if (method_exists("PluginFusioninventoryWizard", $ariane)) {
         $pluginFusioninventoryWizard = new PluginFusioninventoryWizard();
         $a_list = $pluginFusioninventoryWizard->$ariane();
      } else {
         return;
      }

      if (count($a_list) == '0') {
         return;
      }
      echo "<table class='tab_cadre' width='250'>";
      echo "<tr class='tab_bg_1'>";
      echo "<th>";
      echo "<strong>Fil d'ariane</strong>";
      echo "</th>";
      echo "</tr>";
      foreach ($a_list as $name=>$link) {
         echo "<tr class='tab_bg_1'>";
         echo "<td>";
         if ($link == $_GET['wizz']) {
            echo "<img src='".GLPI_ROOT."/pics/right.png'/>";
         } else {
            echo "<img src='".GLPI_ROOT."/pics/right_off.png'/>";
         }
         $getariane = "&ariane=".$ariane;
         if ($link == "w_start") {
            $getariane = "";
         }
         echo " <a href='".GLPI_ROOT."/plugins/fusioninventory/front/wizard.php?wizz=".$link.$getariane."'>".$name."</a>";
         echo "</td>";
         echo "</tr>";
      }

      echo "</table>";
   }


   static function displayButtons($a_buttons, $filariane) {

      $pluginFusioninventoryWizard = new PluginFusioninventoryWizard();

      echo "<style type='text/css'>
      .bgout {
         background-image: url(".GLPI_ROOT."/plugins/fusioninventory/pics/wizard_button.png);
      }
      .bgover {
         background-image: url(".GLPI_ROOT."/plugins/fusioninventory/pics/wizard_button_active.png);
      }
      </style>";
      echo "<center><table width='950'>";
      echo "<tr>";
      echo "<td rowspan='2' align='center'>";
         echo "<table cellspacing='10'>";
         echo "<tr>";
         foreach ($a_buttons as $array) {
            $getariane = '';
            if (isset($array[3]) AND $array[3] != '') {
               $getariane = '&ariane='.$array[3];
            }
            echo "<td class='bgout'
               onmouseover='this.className=\"bgover\"' onmouseout='this.className=\"bgout\"'
               onClick='location.href=\"".GLPI_ROOT."/plugins/fusioninventory/front/wizard.php?wizz=".$array[1].$getariane."\"'
               width='240' height='155' align='center'>";
            echo "<strong>".$array[0]."</strong><br/><br/>";
            if ($array[2] != '') {
               echo "<img src='".GLPI_ROOT."/plugins/fusioninventory/pics/".$array[2]."'/>";
            }
            echo "</td>";
         }
         echo "</tr>";
         echo "</table>";
      echo "</td>";
      echo "<td height='8'></td>";
      echo "</tr>";

      echo "<tr>";
      echo "<td valign='top'>";
      $pluginFusioninventoryWizard->filAriane($filariane);
      echo "</td>";
      echo "</tr>";

      echo "</table></center>";
   }


   static function displayShowForm($a_filariane, $classname, $options = array()) {
      global $LANG,$CFG_GLPI;

      $pluginFusioninventoryWizard = new PluginFusioninventoryWizard();

      echo "<style type='text/css'>
      .bgout {
         background-image: url(".GLPI_ROOT."/plugins/fusioninventory/pics/wizard_button.png);
      }
      .bgover {
         background-image: url(".GLPI_ROOT."/plugins/fusioninventory/pics/wizard_button_active.png);
      }
      </style>";
      echo "<center><table width='950'>";
      echo "<tr>";
      echo "<td valign='top' width='950'>";

      if (isset($_GET['id'])) {
         $class = new $classname;
         if ($_GET['wizz'] == 'w_tasks') {
            initNavigateListItems($classname);
            $class->showQuickForm($_GET['id'], $options['arg1']);
         } else {
            $class->showForm($_GET['id']);
         }

      } else if (isset($_GET['wizz']) AND (strstr($_GET['wizz'], "rules"))) {
  
         $rulecollection = new PluginFusioninventoryRuleImportEquipmentCollection();
         include (GLPI_ROOT . "/plugins/fusioninventory/front/wizzrule.common.php");

      } else if (!empty($options)) {
         if (!isset($options['noadditem'])) {
            echo "<table class='tab_cadre'>";
            echo "<tr>";
            echo "<th>";
            echo "<a href='".$_SERVER["REQUEST_URI"]."&id=0'>Add an item</a>";
            echo "</th>";
            echo "</tr>";
            echo "</table>";
         }
         call_user_func(array($classname, $options['f']), $options['arg1']);

      } else {
         echo "<table class='tab_cadre'>";
         echo "<tr>";
         echo "<th>";
         echo "<a href='".$_SERVER["REQUEST_URI"]."&id=0'>Add an item</a>";
         echo "</th>";
         echo "</tr>";
         echo "</table>";
         Search::manageGetValues($classname);
         Search::showList($classname, $_GET);
      }

      echo "</td>";
      echo "<td valign='top' style='background-color: #e1cc7b;'>";
      $pluginFusioninventoryWizard->filAriane($a_filariane);
      echo "</td>";
      echo "</tr>";

      echo "<tr>";
      echo "<td align='right' style='background-color: #e1cc7b;' height='30'>";
      if (isset($options['finish'])) {
         echo "<input class='submit' type='submit' name='next' value='".$LANG['plugin_fusioninventory']['buttons'][0]."'
               onclick='window.location.href=\"".GLPI_ROOT."/plugins/fusioninventory/\"'/>";

      } else {
         echo "<input class='submit' type='submit' name='next' value='".$LANG['buttons'][11]."'
               onclick='window.location.href=\"".GLPI_ROOT.
         "/plugins/fusioninventory/front/wizard.php?wizz=".PluginFusioninventoryWizard::getNextStep($a_filariane)."\"'/>";
      }
      echo "</form>&nbsp;&nbsp;";
      echo "</td>";
      echo "<td style='background-color: #e1cc7b;'></td>";
      echo "</tr>";

      echo "</table></center>";
   }


   static function getNextStep($ariane) {
      if (method_exists("PluginFusioninventoryWizard", $ariane)) {
         $pluginFusioninventoryWizard = new PluginFusioninventoryWizard();
         $a_list = $pluginFusioninventoryWizard->$ariane();

         $find = 0;
         foreach ($a_list as $link) {
            if ($link == $_GET['wizz']) {
               $find = 1;
            } else {
               if ($find == '1') {
                  return $link."&ariane=".$ariane;
               }
            }
         }
      } else {
         return;
      }
   }


  // ********************* Define fil ariane **********************//


   function filInventoryComputer() {
      return array(
      "choix de l'action"              => "w_start",
      "Type de matériel à inventorier" => "w_inventorychoice",
      "Options d'importation"          => "w_importcomputeroptions",
      "Règles d'import d'ordinateurs"  => "w_importrules",
      "Règles de sélection de l'entité"=> "",
      "Configuration des agents"       => "");
   }



   function filInventoryESX() {
      return array(
      "choix de l'action"                  => "w_start",
      "Type de matériel à inventorier"     => "w_inventorychoice",
      "Gestion des mots de passe"          => "w_credential",
      "Gestion des serveur ESX"            => "w_remotedevices",
      "Règles d'import d'ordinateurs"      => "w_importrules",
      "Gestion des tâches d'exécution"     => "w_tasks",
      "Execution des tâches"               => "w_tasksforcerun",
      "Affichage de la découverte"         => "w_taskslog");
   }


   
   function filInventorySNMP() {
      return array(
      "choix de l'action"                  => "w_start",
      "Type de matériel à inventorier"     => "w_inventorychoice",
      "Authentification SNMP"              => "w_authsnmp",
      "Règles d'import"                    => "w_importrules",
      "Gestion des tâches d'exécution"     => "w_tasks",
      "Execution des tâches"               => "w_tasksforcerun",
      "Affichage de la découverte"         => "w_taskslog");
   }


   function filNetDiscovery() {
      $array = array(
      "choix de l'action"                  => "w_start");
      return array_merge($array, $this->fil_Part_NetDiscovery());
   }


   function filInventorySNMP_Netdiscovery() {
      $array = array(
      "choix de l'action"                  => "w_start",
      "Type de matériel à inventorier"     => "w_snmpdeviceschoice",
      "Choix (decouverte ou inventaire)"   => "");
      return array_merge($array, $this->fil_Part_NetDiscovery());
  }


   function fil_Part_NetDiscovery() {
      return array(
      "Authentification SNMP"              => "w_authsnmp",
      "Règles d'import"                    => "w_importrules",
      "Gestion des tâches d'exécution"     => "w_tasks",
      "Execution des tâches"               => "w_tasksforcerun",
      "Affichage de la découverte"         => "w_taskslog");
   }

   

  // ********************* All wizard display **********************//

   /*
    * First panel of wizard
    */
   static function w_start($ariane='') {
      $a_buttons = array(array('Découvrir le matériel sur le réseau',
                               'w_authsnmp',
                               'networkscan.png',
                               'filNetDiscovery'),
                         array('Inventorier des matériels',
                                'w_inventorychoice',
                                'general_inventory.png',
                                ''));

      echo "<center>Bienvenue dans FusionInventory. Commencer la configuration ?</center><br/>";

      PluginFusioninventoryWizard::displayButtons($a_buttons, $ariane);
   }


   
   static function w_inventorychoice($ariane='') {
      $a_buttons = array(array('Des ordinateurs et leur périphériques',
                               'w_importcomputeroptions',
                               '',
                               'filInventoryComputer'),
                         array('Serveurs ESX',
                               'w_credential',
                               '',
                               'filInventoryESX'),
                         array('Des imprimantes réseaux ou des matériels réseaux',
                                'w_authsnmp',
                                'general_inventory.png',
                                'filInventorySNMP'));

      echo "<center>Bienvenue dans FusionInventory. Commencer la configuration ?</center><br/>";

      PluginFusioninventoryWizard::displayButtons($a_buttons, $ariane);
   }
   


   static function w_authsnmp($ariane='') {
      PluginFusioninventoryWizard::displayShowForm($ariane, "PluginFusinvsnmpConfigSecurity");
   }


   static function w_importrules($ariane='') {
      PluginFusioninventoryWizard::displayShowForm($ariane, "PluginFusioninventoryRuleImportEquipmentCollection");
   }


   
   static function w_credential($ariane='') {
      PluginFusioninventoryWizard::displayShowForm($ariane, "PluginFusioninventoryCredential");
   }



   static function w_remotedevices($ariane='') {
      PluginFusioninventoryWizard::displayShowForm($ariane, "PluginFusioninventoryCredentialIp");
   }


   static function w_tasks($ariane='') {
      unset($_SESSION["plugin_fusioninventory_forcerun"]);
      if (!isset($_GET['sort'])) {
         $_GET['sort'] = 6;
         $_GET['order'] = 'DESC';
      }
      $_GET['target']="task.php";

      $func = '';
      switch ($ariane) {

         case 'filNetDiscovery':
            $func = 'netdiscovery';
            break;

         case 'filInventorySNMP':
            $func = 'snmpquery';
            break;

         case 'filInventoryESX':
            $func = 'ESX';
            break;

      }

      PluginFusioninventoryWizard::displayShowForm($ariane,
               "PluginFusioninventoryTaskjob",
               array("f"=>quickList,
                     "arg1"=>$func));
   }


   static function w_tasksforcerun($ariane='') {
      if (isset($_SESSION["plugin_fusioninventory_forcerun"])) {
         glpi_header($_SERVER["PHP_SELF"]."?wizz=".PluginFusioninventoryWizard::getNextStep($ariane));
         exit;
      }

      if (!isset($_GET['sort'])) {
         $_GET['sort'] = 6;
         $_GET['order'] = 'DESC';
      }
      $_GET['target']="task.php";

      $func = '';
      if ($ariane == "filNetDiscovery") {
         $func = 'netdiscovery';
      }
      PluginFusioninventoryWizard::displayShowForm($ariane,
               "PluginFusioninventoryTaskjob",
               array("f"=>listToForcerun,
                     "arg1"=>$func,
                     "noadditem"=>1));
      
   }


   
   static function w_taskslog($ariane='') {
      if (!isset($_GET['sort'])) {
         $_GET['sort'] = 6;
         $_GET['order'] = 'DESC';
      }
      $_GET['target']="task.php";

      $func = '';
      if ($ariane == "filNetDiscovery") {
         $func = 'netdiscovery';
      }
      PluginFusioninventoryWizard::displayShowForm($ariane,
               "PluginFusioninventoryTaskjob",
               array("f"=>quickListLogs,
                     "arg1"=>'',
                     "noadditem"=>1,
                     "finish"=>1));

   }
}

?>