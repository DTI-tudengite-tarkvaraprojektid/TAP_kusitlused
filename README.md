# Suvepraktika projekt: Quizify

[![N|Solid](https://i.gyazo.com/1a84a9e14a1a87e80e7510f14897aba9.jpg)]()

## Notice:
  - Projekt ei ole lõpetatud, see tähendab, et tuleb veel palju muudatusi!

### Eesmärgid:
  - Luua veebileht mis võimaldab mugavat küsimustiku loomist ning vastajate ja vastuste statistika vaatamist.
  - Toetab erinevaid küsimuse tüüpe küsimustikus: tekst, valikvastused (tulevikus lisame ka muid).
  - Küsimustike saab aktiveerida ja deaktiveerida.
  - Vastused küsimustikule saab tõmmata CSV failina.
  - Igale küsimustikule ei saa vastata rohkem, kui üks kord (IP aadressi kontroll).
 
### Instruktsioon:
  - Failis `app/config.php` Luua ühendus andmebaasiga :
  ```
  $serverHost = "teieserver";
  $serverUsername = "teiekasutaja";
  $serverPassword = "teieparool";
  $dbName = "teieandmebaas";
  
  ```
  - Luua andmebaasi tabelid (vt. andmebaasi skeemi, [tabeliloomine.txt](https://github.com/shxtov/TAP_kusitlused/blob/master/tabeliloomine.txt)).
  - Lisa tabelisse TAP_accounts kasutaja (parool krüpteeritud SHA512).
  - Logi sisse veebilehte oma kasutajanimega ja parooliga.
  - Katseta edasi ise!
  
### Kasutatud tarkvara:
  - PHP 7.0.13
  - jQuery 3.1.1
  - [QRious](https://github.com/neocotic/qrious)
  - Kood kirjutatud Sublime text 3 abil.
  
## Projekti panustasid:
  - Vladislav Šutov, Mark Väljak, Gittan Kaus
  
## Litsents:
  - Vaata [LICENSE.txt](https://github.com/shxtov/TAP_kusitlused/blob/master/LICENSE.txt)

## Andmebaasikeem:
[![N|Solid](https://i.gyazo.com/5aeabad1488951b15b37a1a88a346ac4.png)]()
