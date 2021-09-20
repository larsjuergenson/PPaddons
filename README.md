# PPaddons: Mediawiki addons for the Perrypedia

This Mediawiki extension provides customizations specific to the Mediawiki installation of the [Perrypedia](http://www.perrypedia.proc).

It is not of much use for anyone else, as it is tailored to the particular current needs of the Perrypedia.

The rest of this is in German, the language of the Perrypedia.

## Features

* Drei Spezialseiten, die eine Neo-spezifische Version von existierenden Seiten anbieten:
  * `Spezial:Neue Seiten (PR Neo)`
  * `Spezial:Letzte Änderungen (PR Neo)`
  * `Spezial:Gewünschte Seiten (PR Neo)`

* Zwei Spezialseiten, die eine Version von existierenden Seiten anbieten, die Neo-Seiten (soweit möglich) ausschließen:
  * `Spezial:Neue Seiten (klassische Serie)`
  * `Spezial:Letzte Änderungen (klassische Serie)`

  Konkret werden alle Seiten ausgefiltert, deren Titel auf `PR Neo)` oder `(PR-Neo-Staffel)` endet. Dies erfasst die Schlagwort-Artikel und Staffel-Seiten, jedoch nicht die Handlungszusammenfassungen.

* Eine Parserfunktion `#cscorenum`, die den Wert der `#cscore`-Funktion
  der [Contribution Scores](https://www.mediawiki.org/wiki/Extension:Contribution_Scores) als unformatierte Ganzzahl bereitstellt
  (damit sie, z. B., in Berechnungen verwendet werden kann).

## Abhängigkeiten

* [MediaWiki](https://www.mediawiki.org) >= 1.31
* [ContributionScores](https://www.mediawiki.org/wiki/Extension:Contribution_Scores) == 1.25.0

   **Achtung!** Die Abhängigkeit von ContributionScores ist *nicht* programmatisch hinterlegt, und wird deshalb nicht von MediaWiki
   erkannt. Dies liegt an der veralteten Version der 
   ContributionScores-Extension.
   
   Bei einem **Update** auf ContributionScore >= 1.26 (ab MW 1.34) muss
   PHPaddons angepasst werden (oder, im Notfall, auf [Version 1.2](https://github.com/larsjuergenson/PPaddons/releases/tag/v1.2) downgegradet werden).

## Installation

Wie üblich:

* Das Archiv im `extensions/`-Verzeichnis der MediaWiki-Instalation entpacken.
* Die Extension aktivieren, indem 
  ```
  wfLoadExtension( 'PPaddons' );
  ```
  in `LocalSettings.php` eingefügt wird.

Die Extension keine Konfigurationsoptionen, und berührt keine Datenbanktabellen. Entsprechend ist ein Aufruf von `update.php` nicht notwendig.

## Performance-Implikationen

**Keine.** 

* Die Extension stellt lediglich neue Seiten bereit, die dazu noch direkt die von Mediawiki bereitgestellten Funktionen nutzen, und lediglich eine Abfragebedingung hinzufügen.
* Die bereitgestellte Parserfunktion ist ein dünner Wrapper um 
  ContributionScores und nimmt nur eine einfach Stringmanipulation vor
  (entfernen der Tausenderpunkte).

## Security-Implikationen

**Keine.** 

* Die Extension verarbeitet keine Benutzereingaben und stellt lediglich drei neue Seiten bereit, die dazu noch direkt die von Mediawiki bereitgestellten Funktionen nutzen, und lediglich eine Abfragebedingung hinzufügen.
* Die bereitgestellte Parserfunktion ist ein dünner Wrapper um 
  ContributionScores und nimmt nur eine einfach Stringmanipulation vor
  (entfernen der Tausenderpunkte).

## Technische Notizen

### Implementation

* Neue Spezialseiten werden in Mediawiki erzeugt, indem man in `extension.json` unter dem Key 
 `SpecialPages` eine Liste anlegt, die (englische) Seitennamen auf Klassen mapt. Die Klasse,
 auf die gezeigt wird, muss eine Unterklasse der [`SpecialPages`-Klasse](https://doc.wikimedia.org/mediawiki-core/master/php/classSpecialPage.html) sein.
* Wir machen das einfachste und sauberste, und deklarieren jeweils eine Unterklasse 
  derjenigen Klasse, die die ursprüngliche Spezialseite definiert. Dadurch ist auch 
  gleich das Handling von Filtern u.ä. übernommen.
  * Dabei **muss** der Konstruktor überschrieben werden, auch wenn der selten mehr macht, 
    als den Konstruktor der Elternklasse aufzurufen. Das ist so, weil das 
    Standardargument des Konstruktors (unter gewissen Umständen) den Seitennamen 
    festlegt.
  * Zusätzlich fügt jede Klasse auf die ein oder andere Art und Weise eine 
    Abfragebedingung hinzu, die die Ergebnisse auf PR-Neo-Seiten beschränkt. Wie 
    das funktioniert, variiert leider von Fall zu Fall. Der einleitende Kommentar 
    jeder Klasse beschreibt kurz, was passiert.
* Die Parserfunktionen werden im Hook [onParserFirstInit](https://www.mediawiki.org/wiki/Manual:Hooks/ParserFirstCallInit) registriert, die auf die implentierende PHP-Funktion zeigt. Damit alles funktioniert, muss das neue "Magic Word" (also der Parserfunktionsname) außerdem in `i18n/MagicWords.i18n.php` eingetragen werden.

### Beim Mediawiki-Update zu beachten

*  Falls das Update ein Update der ContributionScore-Extension auf >= 1.26 
   (ab MW 1.34) mit sich bringt, muss
   PHPaddons angepasst werden (oder, im Notfall, auf [Version 1.2](https://github.com/larsjuergenson/PPaddons/releases/tag/v1.2) downgegradet werden).

   Allerdings ist das Problem-Risiko auch hier gering: Schlimmstenfalls funktioniert lediglich die `#cscorenum`-Parserfunktion nicht mehr.

* Das Risiko ist gering: Im schlimmsten Fall funktionieren nach einem Mediawiki-Update 
  die neuen Spezialseiten nicht mehr. 
  
  **Ausname:** Im unwahrscheinlichen Fall, dass sich der Hook [`SpecialNewpagesConditions`](https://www.mediawiki.org/wiki/Manual:Hooks/SpecialNewpagesConditions) ändert, könnte auch die "normale" `Neue Seiten`-Seite beeinträchtigt sein.

* In der Mediawiki-Version 1.31 werden die Klassen, die hier beerbt werden, alle von Dateien im Mediawiki-Verzeichnis `includes/specials` definiert, wo wohl die historisch ältesten dieser Klassen liegen. Neuere sind unter `includes/specialpages` zu finden.

  Sollte eine der neuen Spezialseiten nach einem Mediawiki-Update nicht funktionieren, so sollte also als erstes überprüft werden, ob eine der Elternklassen verschoben, umbenannt oder in einen anderen PHP-Namespace verschoben wurde.
