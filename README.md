# WAF-FI
IP Symcon Kanallisten-Addon für das Harmony Addon von Fonzo

Thema im IP Symcon Forum

https://www.symcon.de/forum/threads/40811-WAF-FI-Kanallisten-Modul-f%C3%BCr-das-Harmony-Modul-Testversion

Sender Logos
------------
Bisher habe ich noch keine freie Quelle gefunden welches es auch erlaubt die Senderlogos hier anzubieten.
Die Logos müssen also selbst besorgt werden und in einen User Ordner geladen werden.
Das Verzeichnis zu diesem Ordner kann man im Konfigurator einstellen. 

In diesen Ordner müssen die Logos geladen werden:

/var/lib/symcon/webfront/user/waffi/images

Im Konfigurator ist dann dieser Path einzugeben:

user/waffi/images

so das die Logos im Browser geladen werden können.

Wo bekomme ich jetzt die Logos her ?

Am einfachsten ist es wenn man sich Picons besorgt

https://github.com/picons/picons/releases/tag/53 
die mit der Endung tar.xz, entpacken geht z.HB. mit 7Zip

Order auch in den Receiver Foren:

https://www.opena.tv/forum111/

Als Größe empfehle ich 100x60



CSV Kanalliste
--------------

Die Kanalliste.csv muss in diesen Ordner geladen werden

/var/lib/symcon/webfront/user/waffi

welcher allerdings auch im Konfigurator geändert werden kann.


Alternativ kann man auch eine Bouquets Datei einspielen und somit auch seine z.b. auf der Dreambox eingespielten Picons nutzen
Wenn man keinen Enigma Receiver hat kann man das ganze auch "emulieren.

Z.B. die aktuelle Senderliste 8Astra) von hier runter laden

https://enigma2-hilfe.de/

und dann mittels DreamboxEdit

https://dreamboxedit.com/category/download/

sich eigene FavoritenListen erstellen.

Hier gibt es dazu eine Anleitung wie DreamBoxEdit funktioniert

https://enigma2-hilfe.de/2017/04/05/senderliste-bearbeiten-und-auf-den-enigma2-receiver-uebertragen/

für das Addons brauchen wir aber nur die Funktion um sich eine Favoriten Liste zu erstellen und zu exportieren.

Wenn man dann keinen Satreceiver nutzt wo die Kanalnummern in der Reihenfolge vergeben werden wie sie in der ExportDatei sequentiell vorhanden sind so kann man die Kanalnummern in der Exportdatei ergänzen. Hierzu muss man hinter dem jeweils kryptischen Eintrag die Kanalnummer hinzufügen getrennt durch EIN Leerleichen. Die Angabe muss dann bei allen Einträgen erfolgen.

PS: das ist nur eine privisorische Anleitung, wird zum Release überarbeitet
