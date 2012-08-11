<?php 
 // $ Id: preferences.php 2879 2009-02-27 00:53:34 Z wishcraft $ 
 // _LANGCODE: En 
 // _CHARSET: UTF-8 
 // Translator: XOOPS Translation Team 

 //%%%%%% Admin-Modul Name AdminGroup %%%%% 
 // Keine Veränderung 
 define( "_MD_AM_MODULEPREF_DOMAIN", "Modul Einstellungen Selection"); 
 define( "_MD_AM_MODULEPREF", "Modul Einstellungen" ); 
 define( "_MD_AM_MESSAGEMODULECOPY", "Die Domain <strong> %s  </ strong> hat keine Daten über <strong> %s  </ strong> <br/> Sie möchten, kopieren Sie die aktuelle Konfiguration?"); 
 define( "_MD_AM_SUCCESSCOPY", "Erfolgreiche Kopieren der Einstellung den Namen:"); 
 define( "_MD_AM_ERRORCOPY", "Fehler beim Kopieren der Einstellung den Namen:"); 
 define( "_AM_DBUPDATED", "_MD_AM_DBUPDATED"); 
 define( "_MD_AM_SITEPREF_DOMAIN", "Domain-Einstellungen Auswahl "); 
 define( "_AM_SELECT_DOMAIN", "Wählen Sie die Domain zum Setzen von Einstellungen" ); 
 define( "_AM_Reich", "Domain:"); 
 define( "_MD_AM_SITEPREF", "Site-Einstellungen"); 
 define( "_MD_AM_SITENAME", "Site name"); 
 define( "_MD_AM_SLOGAN", "Slogan für Ihre Website"); 
 define( "_MD_AM_ADMINML", "Admin-Mail-Adresse"); 
 define( "_MD_AM_LANGUAGE", "Standard-Sprache"); 
 define( "_MD_AM_STARTPAGE", "Modul für Ihre Startseite"); 
 define( "_MD_AM_NONE", "Keine"); 
 define( "_MD_AM_SERVERTZ", "Server-Zeitzone"); 
 define( "_MD_AM_DEFAULTTZ", "Standard-Zeitzone"); 
 define( "_MD_AM_DTHEME", "Standard-Design"); 
 define( "_MD_AM_THEMESET", "Thema-Set"); 
 define( "_MD_AM_ANONNAME", "Benutzername für anonyme Benutzer"); 
 define( "_MD_AM_MINPASS", "Mindestdauer der Passwort erforderlich"); 
 define( "_MD_AM_NEWUNOTIFY", "Benachrichtigen Sie per E-Mail, wenn ein neuer Benutzer registriert ist?"); 
 define( "_MD_AM_SELFDELETE", "Benutzer löschen eigene Rechnung?"); 
 define( "_MD_AM_LOADINGIMG", "Display geladen ... Bild?"); 
 define( "_MD_AM_USEGZIP", "Verwenden Sie gzip Komprimierung?"); 
 define( "_MD_AM_UNAMELVL", "Wählen Sie die Höhe der Strenge für Benutzernamen Filter"); 
 define( "_MD_AM_STRICT", "Strenge (nur Buchstaben und Zahlen)"); 
 define( "_MD_AM_MEDIUM", "Medium"); 
 define( "_MD_AM_LIGHT", "Licht (empfohlen für Multi-Byte-Zeichen)"); 
 define( "_MD_AM_USERCOOKIE", "Name für den Benutzer Cookies."); 
 define( "_MD_AM_USERCOOKIEDSC", "Wenn die Cookie-Name ist, 'Remember me' wird in die Lage versetzt werden für den Benutzer anmelden. Wenn ein Benutzer sich dafür entschieden hat 'Remember me', wird er automatisch eingeloggt. Der Ablauf für die Cookie ist ein Jahr . "); 
 define( "_MD_AM_USEMYSESS", "Benutzerdefinierte Tagung"); 
 define( "_MD_AM_USEMYSESSDSC", "Wählen Sie Ja, um individuell Sitzung Werte."); 
 define( "_MD_AM_SESSNAME", "Session Name"); 
 define( "_MD_AM_SESSNAMEDSC", "Der Name der Session nicht (nur gültig, wenn benutzerdefinierte Session aktiviert ist)"); 
 define( "_MD_AM_SESSEXPIRE", "Session Verfallsdatum"); 
 define( "_MD_AM_SESSEXPIREDSC", "Maximale Dauer der Sitzung im Leerlauf Zeit in Minuten (nur gültig, wenn benutzerdefinierte Session aktiviert ist. Funktioniert nur, wenn Sie PHP4.2.0 oder später .)"); 
 define( "_MD_AM_BANNERS", "Aktivieren Bannerwerbung?"); 
 define( "_MD_AM_MYIP", "Ihre IP-Adresse"); 
 define( "_MD_AM_MYIPDSC", "Diese IP wird nicht als ein Bild für Banner"); 
 define( "_MD_AM_ALWDHTML", "HTML-Tags, die in alle Beiträge."); 
 define( "_MD_AM_INVLDMINPASS", "Ungültiger Wert für die minimale Länge von Passwort."); 
 define( "_MD_AM_INVLDUCOOK", "Ungültiger Wert für usercookie Namen."); 
 define( "_MD_AM_INVLDSCOOK", "Ungültiger Wert für sessioncookie Namen."); 
 define( "_MD_AM_INVLDSEXP", "Ungültiger Wert für die Sitzung Ablauf der Zeit."); 
 define( "_MD_AM_ADMNOTSET", "Admin-Mail ist nicht gesetzt."); 
 define( "_MD_AM_YES", "Ja"); 
 define( "_MD_AM_NO", "Nein"); 
 define( "_MD_AM_DONTCHNG", "nicht ändern!"); 
 define( "_MD_AM_REMEMBER", "chmod 666 Denken Sie daran, diese Datei, um das System zu schreiben, dann richtig."); 
 define( "_MD_AM_IFUCANT", "Wenn Sie nicht ändern können die Berechtigungen können Sie den Rest dieser Datei von Hand."); 

 define( "_MD_AM_MESSAGECOPY", "Die Reich <strong> %s  </ strong> hat keine Präferenzen für %s  propogated für Maskierung, möchten Sie jetzt tun?"); 

 define( "_MD_AM_COMMODE", "Standard-Display-Modus Kommentar"); 
 define( "_MD_AM_COMORDER", "Standard-Kommentare anzeigen Order"); 
 define( "_MD_AM_ALLOWHTML", "Lassen Sie HTML-Tags in Benutzer Kommentare?"); 
 define( "_MD_AM_DEBUGMODE", "Debug-Modus"); 
 define( "_MD_AM_DEBUGMODEDSC", "mehrere Debug-Optionen. Eine laufende Website sollte diese deaktiviert."); 
 define( "_MD_AM_AVATARALLOW", "Lassen Sie benutzerdefinierte avatar hochladen?"); 
 define( "_MD_AM_AVATARMP", "erforderliche Minimum"); 
 define( "_MD_AM_AVATARMPDSC", "Geben Sie die minimale Anzahl von Stellen erforderlich, um eine eigene Avatar Upload"); 
 define( "_MD_AM_AVATARW", "Bild Benutzerbild von max Breite (Pixel)"); 
 define( "_MD_AM_AVATARH", "Bild Benutzerbild von max Höhe (Pixel)"); 
 define( "_MD_AM_AVATARMAX", "Bild Benutzerbild von max Dateigröße (Byte)"); 
 define( "_MD_AM_AVATARCONF", "Benutzerdefinierte Einstellungen avatar"); 
 define( "_MD_AM_CHNGUTHEME", "Ändern Sie alle Benutzer Thema "); 
 define( "_MD_AM_NOTIFYTO", "Wählen Sie die Gruppe, zu der neue Benutzer Anmeldung Mail wird gesendet"); 
 define( "_MD_AM_ALLOWTHEME", "Nutzern erlauben, wählen Sie ein Thema?"); 
 define( "_MD_AM_ALLOWIMAGE", "Erlaube Benutzern, um Bild-Dateien in Stellen?"); 

 define( "_MD_AM_USERACTV", "erfordert die Aktivierung von Benutzer (empfohlen)"); 
 define( "_MD_AM_AUTOACTV", "automatisch aktivieren"); 
 define( "_MD_AM_ADMINACTV", "Aktivierung von Administratoren"); 
 define( "_MD_AM_ACTVTYPE", "Wählen Sie die Art der Aktivierung neu registrierte Benutzer"); 
 define( "_MD_AM_ACTVGROUP", "Wählen Sie die Gruppe, zu der Aktivierung Mail wird gesendet"); 
 define( "_MD_AM_ACTVGROUPDSC", "Gültig nur, wenn Aktivierung von Administratoren ausgewählt"); 
 define( "_MD_AM_USESSL", "SSL verwenden für die Anmeldung?"); 
 define( "_MD_AM_SSLPOST", "SSL-Post Variable name"); 
 define( "_MD_AM_SSLPOSTDSC", "Der Name der Variablen zur Übertragung Tagung Wert per Post. Wenn Sie unsicher sind, setzen Sie einen beliebigen Namen, die schwer zu erraten."); 
 define( "_MD_AM_DEBUGMODE0", "Off"); 
 define( "_MD_AM_DEBUGMODE1", "Enable debug (Inline-Modus)"); 
 define( "_MD_AM_DEBUGMODE2", "Enable debug (Pop-up-Modus)"); 
 define( "_MD_AM_DEBUGMODE3", "Smarty-Templates Debug"); 
 define( "_MD_AM_MINUNAME", "Minimale Länge des Benutzernamens erforderlich"); 
 define( "_MD_AM_MAXUNAME", "Maximale Länge von Benutzername"); 
 define( "_MD_AM_GENERAL", "Allgemeine Einstellungen"); 
 define( "_MD_AM_USERSETTINGS", "User Info-Einstellungen"); 
 define( "_MD_AM_ALLWCHGMAIL", "Nutzern erlauben, E-Mail-Adresse ändern?"); 
 define( "_MD_AM_ALLWCHGMAILDSC", ""); 
 define( "_MD_AM_IPBAN", "IP-Verbot"); 
 define( "_MD_AM_BADEMAILS", "Geben Sie E-Mails, sollten nicht verwendet werden, in der Benutzer-Profil"); 
 define( "_MD_AM_BADEMAILSDSC", "Trennen Sie die einzelnen mit einem <strong> | </ strong>, Groß-und Kleinbuchstaben, regex aktiviert."); 
 define( "_MD_AM_BADUNAMES", "Geben Sie Namen, die nicht gewählt werden, als Benutzername"); 
 define( "_MD_AM_BADUNAMESDSC", "Trennen Sie die einzelnen mit einem <strong> | </ strong>, Groß-und Kleinbuchstaben, regex aktiviert."); 
 define( "_MD_AM_DOBADIPS", "IP Verbote?"); 
 define( "_MD_AM_DOBADIPSDSC", "User aus bestimmten IP-Adressen werden nicht in der Lage, um die Website"); 
 define( "_MD_AM_BADIPS", "Geben Sie die IP-Adressen, die verboten werden sollten von der Website. <br /> Trennen Sie die einzelnen mit einem <strong> | </ strong>, Groß-und Kleinbuchstaben, regex aktiviert."); 
 define( "_MD_AM_BADIPSDSC", "^ aaa.bbb.ccc wird, dass ein Besucher mit einer IP, die mit aaa.bbb.ccc <br /> aaa.bbb.ccc $ wird, dass ein Besucher mit einer IP, endet mit aaa.bbb. ccc <br /> aaa.bbb.ccc wird, dass ein Besucher mit einer IP, die aaa.bbb.ccc "); 
 define( "_MD_AM_PREFMAIN", "Einstellungen Main "); 
 define( "_MD_AM_METAKEY", "Meta-Keywords"); 
 define( "_MD_AM_METAKEYDSC", "Die Keywords Meta-Tag ist eine Reihe von Schlüsselwörtern, die die Inhalte Ihrer Website. Geben Sie Stichworte, mit denen jeweils durch ein Komma oder ein Leerzeichen dazwischen. (Ex XOOPS, PHP, MySQL, Portal-System ) "); 
 define( "_MD_AM_METARATING", "Meta-Bewertung"); 
 define( "_MD_AM_METARATINGDSC", "Die Rating-Meta-Tag definiert Ihrer Website des Alters und der Bewertung der Inhalte"); 
 define( "_MD_AM_METAOGEN", "Allgemein"); 
 define( "_MD_AM_METAO14YRS", "14 Jahre"); 
 define( "_MD_AM_METAOREST", "eingeschränkt"); 
 define( "_MD_AM_METAOMAT", "Ältere"); 
 define( "_MD_AM_METAROBOTS", "Meta-Roboter"); 
 define( "_MD_AM_METAROBOTSDSC", "Die Roboter Tag erklärt, dass sie Suchmaschinen, welche Inhalte zu indizieren und Spinne"); 
 define( "_MD_AM_INDEXFOLLOW", "Index, Follow"); 
 define( "_MD_AM_NOINDEXFOLLOW", "Nein Index, folgen Sie"); 
 define( "_MD_AM_INDEXNOFOLLOW", "Index, Nr. Folgen"); 
 define( "_MD_AM_NOINDEXNOFOLLOW", "Nein-Index, Nr. Folgen"); 
 define( "_MD_AM_METAAUTHOR", "Meta-Autor"); 
 define( "_MD_AM_METAAUTHORDSC", "Der Autor Meta-Tag definiert den Namen der Verfasser des Dokuments zu lesen. Unterstützte Formate sind der Name, E-Mail-Adresse des Webmasters, Firmennamen oder URL."); 
 define( "_MD_AM_METACOPYR", "Meta Copyright"); 
 define( "_MD_AM_METACOPYRDSC", "Der Copyright-Meta-Tag definiert alle Copyright-Bestimmungen Sie offen über Ihre Web-Dokumente."); 
 define( "_MD_AM_METADESC", "Meta-Beschreibung"); 
 define( "_MD_AM_METADESCDSC", "Die Beschreibung von Meta-Tags ist eine allgemeine Beschreibung dessen, was in Ihrem Web-Seite"); 
 define( "_MD_AM_METAFOOTER", "Meta-Tags-und Fußzeile"); 
 define( "_MD_AM_FOOTER", "Fußzeile"); 
 define( "_MD_AM_FOOTERDSC", "Stellen Sie sicher, dass auf den Typ links im vollständigen Pfad ab http://, sonst werden die Links nicht korrekt funktionieren in Seiten-Module."); 
 define( "_MD_AM_CENSOR", "Wortzensur Optionen"); 
 define( "_MD_AM_DOCENSOR", "Enable Zensur von unerwünschten Wörtern?"); 
 define( "_MD_AM_DOCENSORDSC", "Wörter werden zensiert, wenn diese Option aktiviert ist. Diese Option kann deaktiviert werden für eine verbesserte Website Geschwindigkeit."); 
 define( "_MD_AM_CENSORWRD", "Wörter zu zensieren"); 
 define( "_MD_AM_CENSORWRDDSC", "Geben Sie Wörter, die zensiert werden im User-Beiträge. <br /> Trennen Sie die einzelnen mit einem <strong> | </ strong>, nicht beachtet."); 
 define( "_MD_AM_CENSORRPLC", "Bad Worte ersetzt werden, mit:"); 
 define( "_MD_AM_CENSORRPLCDSC", "Zensierte Wörter werden durch die Zeichen in diesem Textfeld"); 

 define( "_MD_AM_SEARCH", "Search Options"); 
 define( "_MD_AM_DOSEARCH", "Globale sucht?"); 
 define( "_MD_AM_DOSEARCHDSC", "Lassen Sie die Suche nach Stellen / Positionen innerhalb Ihrer Website."); 
 define( "_MD_AM_MINSEARCH", "Minimale Länge Stichwort"); 
 define( "_MD_AM_MINSEARCHDSC", "Geben Sie die minimale Länge Stichwort, dass die Benutzer erforderlich sind, um zu suchen"); 
 define( "_MD_AM_MODCONFIG", "Modul-Config-Optionen"); 
 define( "_MD_AM_DSPDSCLMR", "Display Haftungsausschluss?"); 
 define( "_MD_AM_DSPDSCLMRDSC", "Wählen Sie \"Ja\", um den Haftungsausschluss Registrierung Seite"); 
 define( "_MD_AM_REGDSCLMR", "Registrierung Haftungsausschluss"); 
 define( "_MD_AM_REGDSCLMRDSC", "Geben Sie den Text angezeigt werden soll, wie Registrierung disclaimer"); 
 define( "_MD_AM_ALLOWREG", "können neue Benutzer registrieren?"); 
 define( "_MD_AM_ALLOWREGDSC", "Wählen Sie Ja, um die neuen Benutzer registrieren"); 
 define( "_MD_AM_THEMEFILE", "Check-Vorlagen für die Änderungen?"); 
 define( "_MD_AM_THEMEFILEDSC", "Wenn diese Option aktiviert ist, verändert Vorlagen werden automatisch neu, wenn sie angezeigt werden. Sie müssen diese Option auf eine Produktionsstätte."); 
 define( "_MD_AM_CLOSESITE", "Schalten Sie Ihre Website aus?"); 
 define( "_MD_AM_CLOSESITEDSC", "Wählen Sie \"Ja\", um Ihre Website aus, so dass nur Nutzer in ausgewählten Gruppen haben Zugriff auf die Website."); 
 define( "_MD_AM_CLOSESITEOK", "Wählen Sie die Gruppen, die Zugriff auf die Website deaktiviert ist."); 
 define( "_MD_AM_CLOSESITEOKDSC", "Benutzer in der Standard-Webmaster sind immer Zugang."); 
 define( "_MD_AM_CLOSESITETXT", "Grund für das Ausschalten der Website"); 
 define( "_MD_AM_CLOSESITETXTDSC", "Der Text, den wird, wenn die Seite geschlossen wird."); 
 define( "_MD_AM_SITECACHE", "Site-weit Cache"); 
 define( "_MD_AM_SITECACHEDSC", "Caches gesamte Inhalt der Website für einen bestimmten Zeitraum zur Verbesserung der Leistung. Einstellen Website-weit werden die Cache-Modul-Level-Cache, Block-Level-Cache, und Modul-Punkt-Level-Cache, falls vorhanden.") ; 
 define( "_MD_AM_MODCACHE", "Modul-weiten Cache"); 
 define( "_MD_AM_MODCACHEDSC", "Caches Modul Inhalte für einen bestimmten Zeitraum zur Verbesserung der Leistung. Setting-Modul-weit werden die Cache-Modul Punkt-Level-Cache ist."); 
 define( "_MD_AM_NOMODULE", "Es gibt kein Modul, kann zwischengespeichert werden."); 
 define( "_MD_AM_DTPLSET", "Standard-Template-Set"); 
 define( "_MD_AM_SSLLINK", "URL, SSL-Login-Seite befindet sich"); 

 // Hinzugefügt Mailer 
 define( "_MD_AM_MAILER", "E-Mail-Setup"); 
 define( "_MD_AM_MAILER_MAIL", ""); 
 define( "_MD_AM_MAILER_SENDMAIL", ""); 
 define( "_MD_AM_MAILER_", ""); 
 define( "_MD_AM_MAILFROM", "Von-Adresse"); 
 define( "_MD_AM_MAILFROMDESC", ""); 
 define( "_MD_AM_MAILFROMNAME", "FROM name"); 
 define( "_MD_AM_MAILFROMNAMEDESC", ""); 
 // RMV-NOTIFY 
 define( "_MD_AM_MAILFROMUID", "FROM-Benutzer"); 
 define( "_MD_AM_MAILFROMUIDDESC", "Wenn das System sendet eine private Nachricht, die Nutzer sollten offenbar haben sie?"); 
 define( "_MD_AM_MAILERMETHOD", "Mail Delivery-Methode"); 
 define( "_MD_AM_MAILERMETHODDESC", "Methode, um Mail. Voreingestellt ist \" mail \", verwenden Sie nur, wenn andere, die Probleme."); 
 define( "_MD_AM_SMTPHOST", "SMTP-Host (s)"); 
 define( "_MD_AM_SMTPHOSTDESC", "Liste der SMTP-Server zu versuchen, eine Verbindung zu."); 
 define( "_MD_AM_SMTPUSER", "SMTPAuth username"); 
 define( "_MD_AM_SMTPUSERDESC", "Benutzername für die Verbindung zu einem SMTP-Host mit SMTPAuth."); 
 define( "_MD_AM_SMTPPASS", "SMTPAuth password"); 
 define( "_MD_AM_SMTPPASSDESC", "Passwort um die Verbindung zu einem SMTP-Host mit SMTPAuth."); 
 define( "_MD_AM_SENDMAILPATH", "Pfad zu sendmail"); 
 define( "_MD_AM_SENDMAILPATHDESC", "Pfad zum sendmail-Programm (oder Ersatz) auf dem Webserver."); 
 define( "_MD_AM_THEMEOK", "Verfügbare Themen"); 
 define( "_MD_AM_THEMEOKDSC", "Wählen Sie Themen, die Benutzer können wählen, wie die Standard-Theme"); 

 // SOAP-Klauseln 
 define( "_MD_AM_SOAP_CLIENT", "SOAP - SOAP-API"); 
 define( "_MD_AM_SOAP_CLIENTDESC", "Dies ist die Adresse des Seife-Server."); 
 define( "_MD_AM_SOAP_PROVISION", "SOAP - Bereitstellung"); 
 define( "_MD_AM_SOAP_PROVISIONDESC", "Wenn Sie wollen, dass die neuen Benutzer vorhanden, sagen: ja"); 
 define( "_MD_AM_SOAP_PROVISIONGROUP", "SOAP - Rang für die Bereitstellung"); 
 define( "_MD_AM_SOAP_PROVISIONGROUPDESC", "Dies ist der Platz einen neuen Benutzer aus der Soap-Server ist die in."); 

 define( "_MD_AM_SOAP_WSDL", "SOAP - SOAP WSDL"); 
 define( "_MD_AM_SOAP_WSDLDESC", "Wenn Sie eine wdsl Seife Dienst aktivieren Sie diese Option."); 
 define( "_MD_AM_SOAP_USERNAME", "SOAP - SOAP-Benutzername"); 
 define( "_MD_AM_SOAP_USERNAMEDESC", "Dies ist der Benutzername von Ihrem Konto über die SOAP-Server."); 
 define( "_MD_AM_SOAP_PASSWORD", "SOAP - SOAP-Passwort"); 
 define( "_MD_AM_SOAP_PASSWORDDESC", "Wenn Sie ein Passwort mit der Seife Dienst es sich hier ein."); 
 define( "_MD_AM_SOAP_KEEPCLIENT", "SOAP - Client Alive"); 
 define( "_MD_AM_SOAP_KEEPCLIENTDESC", "Keep The Soap Kunde am Leben zu erhalten."); 
 define( "_MD_AM_SOAP_FILTERPERSON", "SOAP - Special Accounts"); 
 define( "_MD_AM_SOAP_FILTERPERSONDESC", "Special Accounts, die mit Xoops-Authentifizierung."); 
 define( "_MD_AM_SOAP_CLIENTPROXYHOST", "SOAP - Proxy Hostname"); 
 define( "_MD_AM_SOAP_CLIENTPROXYHOSTDESC", "SOAP-Server-Proxy-Server."); 
 define( "_MD_AM_SOAP_CLIENTPROXYPORT", "SOAP - Proxy-Port"); 
 define( "_MD_AM_SOAP_CLIENTPROXYPORTDESC", "SOAP-Server-Proxy-Server-Port-Nummer, dh <br>: 0 - 65535"); 
 define( "_MD_AM_SOAP_CLIENTPROXYUSERNAME", "SOAP - Proxy-Benutzername"); 
 define( "_MD_AM_SOAP_CLIENTPROXYUSERNAMEDESC", "SOAP-Server-Proxy-Server-Benutzername"); 
 define( "_MD_AM_SOAP_CLIENTPROXYPASSWORD", "SOAP - Proxy-Passwort"); 
 define( "_MD_AM_SOAP_CLIENTPROXYPASSWORDDESC", "SOAP-Server-Proxy-Server-Passwort."); 
 define( "_MD_AM_SOAP_SOAP_TIMEOUT", "SOAP - SOAP-Timeout"); 
 define( "_MD_AM_SOAP_SOAP_TIMEOUTDESC", "Keep The Soap Query Alive für <strong> xx </ strong> Sekunden."); 
 define( "_MD_AM_SOAP_SOAP_RESPONSETIMEOUT", "SOAP - SOAP-Response Timeout"); 
 define( "_MD_AM_SOAP_SOAP_RESPONSETIMEOUTDESC", "Keep The Soap Query Alive für <strong> xx </ strong> Sekunden."); 
 define( "_MD_AM_SOAP_FIELDMAPPING", "Xoops-Auth-Server Bereichen Mapping"); 
 define( "_MD_AM_SOAP_FIELDMAPPINGDESC", "Beschreiben Sie hier die Zuordnung zwischen der Xoops-Datenbank und dem LDAP-Authentifizierung-System ein.". 
 "<br> <br> Format [Xoops Datenbankfeld] = [Auth System SOAP Attribut]". 
 "<br> Zum Beispiel: E-Mail = Mail". 
 "<br> Trennen Sie die einzelnen mit einem |". 
 "<br> <br>! Für fortgeschrittene Benutzer !!"); 


 // Konstanten Xoops-Authentifizierung 
 define( "_MD_AM_AUTH_CONFOPTION_XOOPS", "XOOPS-Datenbank"); 
 define( "_MD_AM_AUTH_CONFOPTION_LDAP", "Standard-LDAP-Verzeichnis"); 
 define( "_MD_AM_AUTH_CONFOPTION_AD", "Microsoft Active Directory ©"); 
 define( "_MD_AM_AUTH_CONFOPTION_SOAP", "Xoops Soap-Authentifizierung"); 
 define( "_MD_AM_AUTHENTICATION", "Authentication Options"); 
 define( "_MD_AM_AUTHMETHOD", "Authentication Method"); 
 define( "_MD_AM_AUTHMETHODDESC", "Welche Authentifizierungsmethode Möchten Sie für die Unterzeichnung der Nutzer."); 
 define( "_MD_AM_LDAP_MAIL_ATTR", "LDAP - E-Mail-Feld-Name"); 
 define( "_MD_AM_LDAP_MAIL_ATTR_DESC", "Der Name des E-Mail-Attribut in Ihrem LDAP-Verzeichnis-Baum."); 
 define( "_MD_AM_LDAP_NAME_ATTR", "LDAP - Common Name Feldname"); 
 define( "_MD_AM_LDAP_NAME_ATTR_DESC", "Der Name der Common Name Attribut in Ihrem LDAP-Verzeichnis."); 
 define( "_MD_AM_LDAP_SURNAME_ATTR", "LDAP - Name Feld Name"); 
 define( "_MD_AM_LDAP_SURNAME_ATTR_DESC", "Der Name der Name-Attribut in Ihrem LDAP-Verzeichnis."); 
 define( "_MD_AM_LDAP_GIVENNAME_ATTR", "LDAP - Vorname Feldname"); 
 define( "_MD_AM_LDAP_GIVENNAME_ATTR_DSC", "Der Name der Vorname Attribut in Ihrem LDAP-Verzeichnis."); 
 define( "_MD_AM_LDAP_BASE_DN", "LDAP - Base DN"); 
 define( "_MD_AM_LDAP_BASE_DN_DESC", "Die Basis-DN (Distinguished Name) des LDAP-Verzeichnis-Baum."); 
 define( "_MD_AM_LDAP_PORT", "LDAP - Port-Nummer"); 
 define( "_MD_AM_LDAP_PORT_DESC", "Die Port-Nummer für den Zugriff auf Ihre LDAP-Verzeichnis-Server."); 
 define( "_MD_AM_LDAP_SERVER", "LDAP - Server-Name"); 
 define( "_MD_AM_LDAP_SERVER_DESC", "Der Name der LDAP-Verzeichnis-Server."); 

 define( "_MD_AM_LDAP_MANAGER_DN", "DN des LDAP-Manager"); 
 define( "_MD_AM_LDAP_MANAGER_DN_DESC", "Der DN des Nutzers ermöglichen, um die Suche (z. B. Manager)"); 
 define( "_MD_AM_LDAP_MANAGER_PASS", "Passwort des LDAP-Manager"); 
 define( "_MD_AM_LDAP_MANAGER_PASS_DESC", "Das Passwort des Benutzers ermöglichen, um die Suche"); 
 define( "_MD_AM_LDAP_VERSION", "LDAP-Protokoll Version"); 
 define( "_MD_AM_LDAP_VERSION_DESC", "Die LDAP-Protokoll Version: 2 oder 3"); 
 define( "_MD_AM_LDAP_USERS_BYPASS", "Benutzer erlaubt LDAP-Authentifizierung zu umgehen"); 
 define( "_MD_AM_LDAP_USERS_BYPASS_DESC", "Benutzer authentifiziert werden, um mit einheimischen XOOPS-Methode"); 

 define( "_MD_AM_LDAP_USETLS", "Use TLS-Verbindung"); 
 define( "_MD_AM_LDAP_USETLS_DESC", "Verwenden Sie eine TLS (Transport Layer Security)-Verbindung. TLS-Standard-389-Port-Nummer <BR>". 
 "Und die LDAP-Version muss auf 3 gesetzt."); 

 define( "_MD_AM_LDAP_LOGINLDAP_ATTR", "LDAP-Attribut bei der Suche der Benutzer"); 
 define( "_MD_AM_LDAP_LOGINLDAP_ATTR_D", "Bei der Login-Name den Einsatz in der DN-Option aktiviert ist, muss der Login-Name XOOPS"); 
 define( "_MD_AM_LDAP_LOGINNAME_ASDN", "Login-Name den Einsatz in der DN"); 
 define( "_MD_AM_LDAP_LOGINNAME_ASDN_D", "Die XOOPS Login-Name wird in die LDAP-DN (z. B.: uid = <loginname>, dc = xoops, dc = org) <br> Der Eintrag wird direkt in den LDAP-Server, ohne die Suche") ; 

 define( "_MD_AM_LDAP_FILTER_PERSON", "Die Suche Filter LDAP-Abfrage zu finden user"); 
 define( "_MD_AM_LDAP_FILTER_PERSON_DESC", "Besondere LDAP-Filter zu finden Benutzer. @ @ @ @ loginname ist ersetzt durch den Benutzer die Login-Namen <br> muss leer sein, wenn Sie nicht wissen, was sie tun!". 
 "<br /> Ex: (& (objectclass = person) (sAMAccountName = loginname @ @ @ @)) für AD". 
 "<br /> Ex: (& (objectClass = InetOrgPerson) (uid = @ @ @ @ loginname)) für LDAP"); 

 define( "_MD_AM_LDAP_DOMAIN_NAME", "Der Domain-Name"); 
 define( "_MD_AM_LDAP_DOMAIN_NAME_DESC", "Windows-Domain-Namen. für ADS-und NT-Server nur"); 

 define( "_MD_AM_LDAP_PROVIS", "Automatische xoops provisionning Konto"); 
 define( "_MD_AM_LDAP_PROVIS_DESC", "Erstellen xoops Benutzer-Datenbank, wenn nicht vorhanden"); 

 define( "_MD_AM_LDAP_PROVIS_GROUP", "Einfluss auf Standard-Gruppe"); 
 define( "_MD_AM_LDAP_PROVIS_GROUP_DSC", "Der neue Benutzer zuordnen, um diese Gruppen"); 

 define( "_MD_AM_LDAP_FIELD_MAPPING_ATTR", "Xoops-Auth-Server Bereichen Mapping"); 
 define( "_MD_AM_LDAP_FIELD_MAPPING_DESC", "Beschreiben Sie hier die Zuordnung zwischen der Xoops-Datenbank und dem LDAP-Authentifizierung-System ein.". 
 "<br /> <br /> Format [Xoops Datenbankfeld] = [Auth System LDAP Attribut]". 
 "<br /> Zum Beispiel: E-Mail = Mail". 
 "<br /> Trennen Sie die einzelnen mit einem |". 
 "<br /> <br />! Für fortgeschrittene Benutzer !!"); 
 
 define( "_MD_AM_LDAP_PROVIS_UPD", "Pflegen xoops provisionning Konto"); 
 define( "_MD_AM_LDAP_PROVIS_UPD_DESC", "Die Xoops Benutzerkonto ist immer synchron mit dem Authentication Server"); 


 define( "_MD_AM_CPANEL", "Control Panel GUI"); 
 define( "_MD_AM_CPANELDSC", "Für Backend"); 

 define( "_MD_AM_WELCOMETYPE", "Senden mit Genugtuung Nachricht"); 
 define( "_MD_AM_WELCOMETYPE_DESC", "Die Art und Weise der Entsendung ein gemütliches Nachricht an einen Benutzer auf seinem erfolgreichen Registrierung."); 
 define( "_MD_AM_WELCOMETYPE_EMAIL", "Email"); 
 define( "_MD_AM_WELCOMETYPE_PM", "Message"); 
 define( "_MD_AM_WELCOMETYPE_BOTH", "E-Mail und Nachricht"); 

 ?>