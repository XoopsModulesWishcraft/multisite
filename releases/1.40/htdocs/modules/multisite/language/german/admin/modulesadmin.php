<?php 
 // $ Id: modulesadmin.php 2411 2008-11-14 21:01:07 Z julionc $ 
 // _LANGCODE: En 
 // _CHARSET: UTF-8 
 // Translator: XOOPS Translation Team 

 //%%%%%% Dateiname modulesadmin.php %%%%% 
 define( "_MD_AM_MODADMIN", "Module Administration"); 
 define( "_MD_AM_MODULE", "Modul"); 
 define( "_MD_AM_NEWReich", "New Reich"); 
 define( "_MD_AM_VERSION", "Version"); 
 define( "_MD_AM_LASTUP", "Letztes Update"); 
 define( "_MD_AM_DEACTIVATED", "Deaktiviert"); 
 define( "_MD_AM_ACTION", "Action"); 
 define( "_MD_AM_DEACTIVATE", "Deaktivieren"); 
 define( "_MD_AM_ACTIVATE", "Activate"); 
 define( "_MD_AM_UPDATE", "Update"); 
 define( "_MD_AM_DUPEN", "Duplizieren-Eintrag in Modulen Tisch! "); 
 define( "_MD_AM_DEACTED", "Das ausgewählte Modul wurde deaktiviert. Sie können nun sicher deinstallieren Sie das Modul."); 
 define( "_MD_AM_ACTED", "Das ausgewählte Modul wurde aktiviert!"); 
 define( "_MD_AM_UPDTED", "Das ausgewählte Modul wurde aktualisiert!"); 
 define( "_MD_AM_SYSNO", "System-Modul kann nicht deaktiviert werden."); 
 define( "_MD_AM_STRTNO", "Dieses Modul ist als Standard-Startseite. Bitte ändern Sie die Start-Modul, unabhängig von Ihren Einstellungen."); 
 define( "_MD_AM_Reich_IN", "Reich"); 
 // Hinzugefügt RC2 
 define( "_MD_AM_PCMFM", "Bitte bestätigen Sie:"); 

 // Hinzugefügt RC3 
 define( "_MD_AM_ORDER", "Order"); 
 define( "_MD_AM_ORDER0", "(0 = verbergen)"); 
 define( "_MD_AM_ACTIVE", "Aktiv"); 
 define( "_MD_AM_INACTIVE", "Inaktiv"); 
 define( "_MD_AM_NOTINSTALLED", "nicht installiert"); 
 define( "_MD_AM_NOCHANGE", "keine Veränderung"); 
 define( "_MD_AM_INSTALL", "Install"); 
 define( "_MD_AM_UNINSTALL", "Deinstallieren"); 
 define( "_MD_AM_SUBMIT", "Submit"); 
 define( "_MD_AM_CANCEL", "Abbrechen"); 
 define( "_MD_AM_DBUPDATE", "Datenbank erfolgreich aktualisiert!"); 
 define( "_MD_AM_BTOMADMIN", "Zurück zur Seite Modul Administration"); 

 // %s  steht für Modul 
 define( "_MD_AM_FAILINS", "konnte nicht installiert %s "); 
 define( "_MD_AM_FAILACT", "nicht zu aktivieren %s ."); 
 define( "_MD_AM_FAILDEACT", "nicht zu deaktivieren %s "); 
 define( "_MD_AM_FAILUPD", "Unable to update %s "); 
 define( "_MD_AM_FAILUNINS", "nicht um das Programm zu deinstallieren %s "); 
 define( "_MD_AM_FAILORDER", "konnte nicht neu %s "); 
 define( "_MD_AM_FAILWRITE", "Unable to write to main menu."); 
 define( "_MD_AM_ALEXISTS", "Modul %s  ist bereits vorhanden."); 
 define( "_MD_AM_ERRORSC", "Error (s ):"); 
 define( "_MD_AM_OKINS", "Modul %s  erfolgreich installiert."); 
 define( "_MD_AM_OKACT", "Modul %s  erfolgreich aktiviert."); 
 define( "_MD_AM_OKDEACT", "Modul %s  erfolgreich deaktiviert."); 
 define( "_MD_AM_OKUPD", "Modul %s  erfolgreich aktualisiert."); 
 define( "_MD_AM_OKUNINS", "Modul %s  erfolgreich deinstalliert."); 
 define( "_MD_AM_OKORDER", "Modul %s  erfolgreich geändert."); 

 define( "_MD_AM_RUSUREINS "," Drücken Sie die Taste unten, um die Installation des Moduls"); 
 define( "_MD_AM_RUSUREUPD "," Drücken Sie die Taste unten, um die Aktualisierung dieses Modul"); 
 define( "_MD_AM_RUSUREUNINS", "Sind Sie sicher, dass Sie möchten, deinstallieren Sie dieses Modul? "); 
 define( "_MD_AM_LISTUPBLKS", "Die folgenden Blöcke werden aktualisiert. <br /> Wählen Sie die Blöcke, die Inhalte (Vorlagen und Optionen) kann überschrieben werden. <br />"); 
 define( "_MD_AM_NEWBLKS", "Neue Blöcke"); 
 define( "_MD_AM_DEPREBLKS", "Veraltet Blocks "); 

 // Hinzugefügt 2.3 - julionc 
 define( "_MD_AM_INSTALLING", "Installation"); 
 define( "_MD_AM_TABLE_RESERVED", " %s  ist ein reservierter Tisch! "); 
 define( "_MD_AM_CREATE_TABLES "," Erstellen von Tabellen ..."); 
 define( "_MD_AM_TABLE_CREATED", "Tabelle %s  erstellt "); 
 define( "_MD_AM_INSERT_DATA", "Daten, die Tabelle %s "); 
 define( "_MD_AM_INSERT_DATA_FAILD", "Konnte %s  nicht einfügen in die Datenbank. "); 
 define( "_MD_AM_INSERT_DATA_DONE", "Module Daten eingefügt erfolgreich."); 
 define( "_MD_AM_MODULEID", "Modul-ID: %s "); 
 define( "_MD_AM_SQL_FOUND "," SQL-Datei finden Sie unter %s "); 
 define( "_MD_AM_SQL_NOT_FOUND "," SQL-Datei nicht gefunden in %s "); 
 define( "_MD_AM_SQL_NOT_CREATE", "Fehler: konnte nicht erstellt werden %s "); 
 define( "_MD_AM_SQL_NOT_VALID", " %s  ist keine gültige SQL!"); 

 define( "_MD_AM_GROUP_ID "," Gruppen-ID: %s "); 
 define( "_MD_AM_NAME", "Name:"); 
 define( "_MD_AM_VALUE", "Wert:"); 

 /*Templates */ 
 define( "_MD_AM_TEMPLATES_ADD "," Hinzufügen von Vorlagen ..."); 
 define( "_MD_AM_TEMPLATES_DELETE", "Löschen von Vorlagen ..."); 
 define( "_MD_AM_TEMPLATES_UPDATE", "Aktualisieren Vorlagen ..."); 

 define( "_MD_AM_TEMPLATE_ID "," Template-ID: %s "); 

 define( "_MD_AM_TEMPLATE_ADD_DATA", "Template %s  in der Datenbank"); 
 define( "_MD_AM_TEMPLATE_ADD_ERROR", "Fehler: Konnte keine insert %s , um die Datenbank. "); 
 define( "_MD_AM_TEMPLATE_COMPILED", "Template %s  erstellt "); 
 define( "_MD_AM_TEMPLATE_COMPILED_FAILED", "Fehler: Failed Zusammenstellung Vorlage %s "); 
 define( "_MD_AM_TEMPLATE_DELETE_DATA", "Template %s  aus der Datenbank gelöscht. "); 
 define( "_MD_AM_TEMPLATE_DELETE_DATA_FAILD", "Fehler: konnte nicht gelöscht Vorlage %s  aus der Datenbank. "); 
 define( "_MD_AM_TEMPLATE_INSERT_DATA", "Template %s  eingefügt, um zu der Datenbank. "); 
 define( "_MD_AM_TEMPLATE_RECOMPILE", "Template %s  neu "); 
 define( "_MD_AM_TEMPLATE_RECOMPILE_FAILD", "Fehler: Template %s  rekompilieren nicht"); 
 define( "_MD_AM_TEMPLATE_RECOMPILE_ERROR", "Fehler: Konnte keine rekompilieren Vorlage %s "); 
 define( "_MD_AM_TEMPLATE_DELETE_OLD_ERROR", "Fehler: konnte nicht gelöscht alten Vorlage %s  Aborting Aktualisierung dieser Datei. "); 
 define( "_MD_AM_TEMPLATE_UPDATE", "Template %s  aktualisiert."); 
 define( "_MD_AM_TEMPLATE_UPDATE_ERROR", "Fehler: Konnte %s  nicht aktualisieren Vorlage."); 

 /*Blocks */ 
 define( "_MD_AM_BLOCKS_ADD "," Hinzufügen von Blöcken ..."); 
 define( "_MD_AM_BLOCKS_DELETE", "Block löschen ..."); 
 define( "_MD_AM_BLOCKS_REBUILD "," Wiederaufbau blockiert ..."); 

 define( "_MD_AM_BLOCK_ID", "Block-ID: %s "); 

 define( "_MD_AM_BLOCK_ACCESS", "Block hinzugefügt Zugriffsrechte"); 
 define( "_MD_AM_BLOCK_ACCESS_ERROR", "Fehler: Konnte nicht hinzufügen Zugang rechts"); 
 define( "_MD_AM_BLOCK_ADD", "Block %s  hinzugefügt "); 
 define( "_MD_AM_BLOCK_ADD_ERROR", "Fehler: Konnte nicht hinzufügen Block %s , um die Datenbank! "); 
 define( "_MD_AM_BLOCK_ADD_ERROR_DATABASE", "Fehler in der Datenbank: %s "); 
 define( "_MD_AM_BLOCK_CREATED", "Block %s  erstellt "); 
 define( "_MD_AM_BLOCK_DELETE", "Block %s  gelöscht. "); 
 define( "_MD_AM_BLOCK_DELETE_DATA", "Block Vorlage %s  aus der Datenbank gelöscht. "); 
 define( "_MD_AM_BLOCK_DELETE_ERROR", "Fehler: konnte nicht gelöscht Block %s "); 
 define( "_MD_AM_BLOCK_DELETE_TEMPLATE_ERROR", "Fehler: konnte nicht gelöscht Blockvorlage %s  aus der Datenbank"); 
 define( "_MD_AM_BLOCK_DEPRECATED", "Block Vorlage %s  abgelehnt "); 
 define( "_MD_AM_BLOCK_DEPRECATED_ERROR", "Fehler: konnte nicht entfernt deprecated Blockvorlage."); 
 define( "_MD_AM_BLOCK_UPDATE", "Block %s  aktualisiert."); 

 /*Configs */ 
 define( "_MD_AM_GONFIG_ID", "Config-ID: %s "); 
 define( "_MD_AM_MODULE_DATA_ADD "," Hinzufügen von Daten-Modul-Konfiguration ..."); 
 define( "_MD_AM_MODULE_DATA_DELETE", "Löschen von Modul Konfigurationsoptionen ..."); 
 define( "_MD_AM_MODULE_DATA_UPDATE", "Module Daten aktualisiert werden. "); 

 define( "_MD_AM_CONFIG_ADD", "Config Option hinzugefügt"); 
 define( "_MD_AM_CONFIG_DATA_ADD", "Config %s  in der Datenbank"); 
 define( "_MD_AM_CONFIG_DATA_ADD_ERROR", "Fehler: Kann nicht einfügen config %s , um die Datenbank. "); 
 define( "_MD_AM_GONFIG_DATA_DELETE", "Config Daten aus der Datenbank gelöscht. "); 
 define( "_MD_AM_CONFIG_DATA_DELETE_ERROR", "Fehler: konnte nicht gelöscht config Daten aus der Datenbank"); 

 /*Zugriff */ 
 define( "_MD_AM_GROUP_SETTINGS_ADD", "Einstellung Gruppe Rechte ..."); 

 define( "_MD_AM_GROUP_PERMS_DELETE_ERROR", "Fehler: konnte nicht gelöscht Gruppe Berechtigungen"); 
 define( "_MD_AM_GROUP_PERMS_DELETED", "Gruppe Berechtigungen gelöscht"); 

 define( "_MD_AM_ACCESS_ADMIN_ADD", "Added admin Zugriffsrechte für Gruppen-ID %s "); 
 define( "_MD_AM_ACCESS_ADMIN_ADD_ERROR", "Fehler: Konnte nicht hinzufügen admin Zugriffsrechte für Gruppen-ID %s "); 
 define( "_MD_AM_ACCESS_USER_ADD_ERROR", "User Zugang für Gruppen-ID: %s "); 
 define( "_MD_AM_ACCESS_USER_ADD_ERROR_ERROR", "Fehler: Konnte nicht hinzufügen Benutzer Zugriffsrechte für Gruppen-ID: %s "); 

 // Modul ausführen spezifischen Installationsskript, wenn eine 
 define( "_MD_AM_FAILED_EXECUTE", "Failed to execute %s "); 
 define( "_MD_AM_FAILED_SUCESS", " %s  erfolgreich ausgeführt. "); 

 define( "_MD_AM_DELETE_ERROR", "Fehler: Konnte %s  nicht gelöscht"); 
 define( "_MD_AM_UPDATE_ERROR", "Fehler: Kann nicht aktualisieren %s "); 
 define( "_MD_AM_DELETE_MOD_TABLES", "Löschen von Tabellen Modul ..."); 

 define( "_MD_AM_COMMENTS_DELETE", "Löschen von Kommentaren ..."); 
 define( "_MD_AM_COMMENTS_DELETE_ERROR", "Fehler: Kann nicht löschen"); 
 define( "_MD_AM_COMMENTS_DELETED", "Kommentare gelöscht"); 

 define( "_MD_AM_NOTIFICATIONS_DELETE", "Löschen von Mitteilungen ..."); 
 define( "_MD_AM_NOTIFICATIONS_DELETE_ERROR", "Fehler: konnte nicht gelöscht Benachrichtigungen"); 
 define( "_MD_AM_NOTIFICATIONS_DELETED", "Benachrichtigungen löschen"); 

 define( "_MD_AM_TABLE_DROPPED", "Tabelle %s  fallen! "); 
 define( "_MD_AM_TABLE_DROPPED_ERROR", "Fehler: Konnte keine DROP TABLE %s "); 
 define( "_MD_AM_TABLE_DROPPED_FAILDED", "Fehler: Nicht erlaubt, um DROP TABLE %s !"); 

 ?> 
