# WAF-FI
IP Symcon Kanallisten-Addon für das Harmony Addon von Fonzo

Thema im IP Symcon Forum

https://www.symcon.de/forum/threads/40811-WAF-FI-Kanallisten-Modul-f%C3%BCr-das-Harmony-Modul-Testversion

# Sender Logos
------------
Bisher habe ich noch keine freie Quelle gefunden welches es auch erlaubt die Senderlogos hier anzubieten.
Die Logos müssen also selbst besorgt werden und in einen User Ordner geladen werden.
Das Verzeichnis zu diesem Ordner kann man im Konfigurator einstellen. 

In diesen Ordner müssen die Logos geladen werden:
```
/var/lib/symcon/webfront/user/waffi/images
```
Im Konfigurator ist dann dieser Path einzugeben:
```
user/waffi/images
```
so das die Logos im Browser geladen werden können.

Wo bekomme ich jetzt die Logos her ?

Am einfachsten ist es wenn man sich Picons besorgt

https://github.com/picons/picons/releases/tag/53 
die mit der Endung tar.xz, entpacken geht z.HB. mit 7Zip

Order auch in den Receiver Foren:

https://www.opena.tv/forum111/

Als Größe empfehle ich 100x60


# CSV Kanalliste
--------------

Die __Kanalliste.csv__ (Beispielname) muss in diesen Ordner geladen werden
```
/var/lib/symcon/webfront/user/waffi
```
der Name und der PATH der Datei kann im Konfigurator geändert werden.
Alternativ kann man die Datei auch über den Konfigurator hoch laden.

## Aufbau der Kanalliste
#### Format der normalen CSV Datei

Die enzelnen Spalten sind durch ein Semikolon ";" getrennt und es dürfen keine Sonderzeichen bei den Angaben verwendet werden.
Die Spalten 1 und 2 dürfen nur ganze Zahlen enthalten.
- sortOrder --> Nach dieser Spalte werden die Sender im WebFront sortiert
- channelNumber --> Die Kanalnummer die an den Receiver / TV geschickt wird
- channelName --> Der Name vom Kanal
- channelImage --> Das Bild mit dem Senderlogo im IMAGE Verzeichnis welches im Konfigurator ausgewählt wurde

```
sortOrder;channelNumber;channelName;channelImage
1;1;ARD;1_HD.png
2;2;ZDF;ZDF_HD.png
3;3;RTL;RTL.png
4;4;Sat1;Sat1.png
5;5;PRO7;Pro7.png
6;6;Vox;Vox.png
7;7;Kabel1;kabel1.png
8;8;RTL2;RTL2.png
9;9;SuperRTL;Super_RTL.png
10;12;3Sat;3sat_HD.png
11;13;Welt;
12;14;NTV;NTV.png
13;15;Phoenix;phoenix_HD.png
14;16;Tele5;Tele5.png
15;17;NeoHD;neo_HD.png
16;50;Eurosport;Eurosport-HD.png
17;51;Sport1;Sport1-HD.png
18;64;DMax;DMAX-HD.png
19;69;7Maxx;
20;75;Sixx;SIXX.png
21;89;K1Doku;kabel_eins_doku.png
22;90;N24Doku;
23;93;ZDFInfo;zdfinfo_HD.png
24;99;MTV;MTV.png
```

#### Format einer normalen Bouquet Datei (ohne Kanalangabe)
Hierbei werden nur die Einträge beachtet welche mit __#SERVICE__ beginnen, 
alle weiteren Einträge wie z.B. __#NAME__ werden ignoriert.

Die Zeile __#SERVICE__ darf außer der __Kennung__ welche durch __ein Leerzeichen__ getrennt ist keine weiteren Angaben enthalten.
```
#NAME Favourites (TV)
#SERVICE 1:0:19:283D:3FB:1:C00000:0:0:0:
#SERVICE 1:0:19:2B66:3F3:1:C00000:0:0:0:
#SERVICE 1:0:19:EF10:421:1:C00000:0:0:0:
#SERVICE 1:0:19:EF74:3F9:1:C00000:0:0:0:
#SERVICE 1:0:19:EF75:3F9:1:C00000:0:0:0:
#SERVICE 1:0:19:EF11:421:1:C00000:0:0:0:
#SERVICE 1:0:19:EF76:3F9:1:C00000:0:0:0:
#SERVICE 1:0:19:EF15:421:1:C00000:0:0:0:
#SERVICE 1:0:19:2E9B:411:1:C00000:0:0:0:
#SERVICE 1:0:19:2B8E:3F2:1:C00000:0:0:0:
#SERVICE 1:0:19:5274:41D:1:C00000:0:0:0:
#SERVICE 1:0:19:EF14:421:1:C00000:0:0:0:
#SERVICE 1:0:19:285B:401:1:C00000:0:0:0:
#SERVICE 1:0:19:1519:455:1:C00000:0:0:0:
#SERVICE 1:0:19:2B7A:3F3:1:C00000:0:0:0:
#SERVICE 1:0:19:30D6:413:1:C00000:0:0:0:
#SERVICE 1:0:19:1581:41F:1:C00000:0:0:0:
#SERVICE 1:0:19:151A:455:1:C00000:0:0:0:
#SERVICE 1:0:19:EF78:3F9:1:C00000:0:0:0:
#SERVICE 1:0:19:EF77:3F9:1:C00000:0:0:0:
#SERVICE 1:0:1:4465:453:1:C00000:0:0:0:
#SERVICE 1:0:1:30:5:85:C00000:0:0:0:
#SERVICE 1:0:19:2BA2:3F2:1:C00000:0:0:0:
#SERVICE 1:0:19:2777:409:1:C00000:0:0:0:
```

#### Format einer Bouquet mit Kanalangabe
Hierbei werden nur die Einträge beachtet welche mit __#SERVICE__ beginnen, 
alle weiteren Einträge wie z.B. __#NAME__ werden ignoriert.

Die Zeile __#SERVICE__ darf nur die __Kennung__ und die __Kanalnummer__ welche jeweils durch __ein Leerzeichen__ getrennt sind enthalten.
```
#NAME Favourites (TV)
#SERVICE 1:0:19:283D:3FB:1:C00000:0:0:0: 1
#SERVICE 1:0:19:2B66:3F3:1:C00000:0:0:0: 2
#SERVICE 1:0:19:EF10:421:1:C00000:0:0:0: 3
#SERVICE 1:0:19:EF74:3F9:1:C00000:0:0:0: 4
#SERVICE 1:0:19:EF75:3F9:1:C00000:0:0:0: 5
#SERVICE 1:0:19:EF11:421:1:C00000:0:0:0: 6
#SERVICE 1:0:19:EF76:3F9:1:C00000:0:0:0: 7
#SERVICE 1:0:19:EF15:421:1:C00000:0:0:0: 8
#SERVICE 1:0:19:2E9B:411:1:C00000:0:0:0: 9
#SERVICE 1:0:19:2B8E:3F2:1:C00000:0:0:0: 12
#SERVICE 1:0:19:5274:41D:1:C00000:0:0:0: 13
#SERVICE 1:0:19:EF14:421:1:C00000:0:0:0: 14
#SERVICE 1:0:19:285B:401:1:C00000:0:0:0: 15
#SERVICE 1:0:19:1519:455:1:C00000:0:0:0: 16
#SERVICE 1:0:19:2B7A:3F3:1:C00000:0:0:0: 17
#SERVICE 1:0:19:30D6:413:1:C00000:0:0:0: 50
#SERVICE 1:0:19:1581:41F:1:C00000:0:0:0: 51
#SERVICE 1:0:19:151A:455:1:C00000:0:0:0: 64
#SERVICE 1:0:19:EF78:3F9:1:C00000:0:0:0: 69
#SERVICE 1:0:19:EF77:3F9:1:C00000:0:0:0: 75
#SERVICE 1:0:1:4465:453:1:C00000:0:0:0: 89
#SERVICE 1:0:1:30:5:85:C00000:0:0:0: 90
#SERVICE 1:0:19:2BA2:3F2:1:C00000:0:0:0: 93
#SERVICE 1:0:19:2777:409:1:C00000:0:0:0: 99
```



Alternativ kann man auch eine Bouquets Datei einspielen und somit auch seine z.b. auf der Dreambox eingespielten Picons nutzen
Wenn man keinen Enigma Receiver hat kann man das ganze auch "emulieren.

Z.B. die aktuelle Senderliste (Astra) von hier runter laden

https://enigma2-hilfe.de/

und dann mittels DreamboxEdit

https://dreamboxedit.com/category/download/

sich eigene FavoritenListen erstellen.

Hier gibt es dazu eine Anleitung wie DreamBoxEdit funktioniert

https://enigma2-hilfe.de/2017/04/05/senderliste-bearbeiten-und-auf-den-enigma2-receiver-uebertragen/

für das Addons brauchen wir aber nur die Funktion um sich eine Favoriten Liste zu erstellen und zu exportieren.

Wenn man dann keinen Satreceiver nutzt wo die Kanalnummern in der Reihenfolge vergeben werden wie sie in der ExportDatei sequentiell vorhanden sind so kann man die Kanalnummern in der Exportdatei ergänzen. Hierzu muss man hinter dem jeweils kryptischen Eintrag die Kanalnummer hinzufügen getrennt durch EIN Leerleichen. Die Angabe muss dann bei allen Einträgen erfolgen.


PS: das ist nur eine provisorische Anleitung, wird zum Release überarbeitet
