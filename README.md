
# CodeForge

**CodeForge** ist eine benutzerfreundliche Desktop-Anwendung, die es Entwicklern ermöglicht, Code-Snippets in verschiedenen Programmiersprachen zu schreiben, mit Syntax-Highlighting anzuzeigen und in einer MySQL-Datenbank zu speichern. Die Anwendung wurde mit **Python**, **PyQt5** und **MySQL** entwickelt.

## Inhaltsverzeichnis
- [Funktionen](#funktionen)
- [Voraussetzungen](#voraussetzungen)
- [Bilder](#Bilder)
- [Installation](#installation)
- [Datenbankeinrichtung](#datenbankeinrichtung)
- [Verwendung](#verwendung)
- [Code-Struktur](#code-struktur)
- [Fehlersuche](#fehlersuche)
- [Lizenz](#lizenz)

## Funktionen

- **Mehrsprachige Unterstützung**: Wähle aus Sprachen wie Python, Java, C++, JavaScript, HTML, CSS, PHP, Rust, C und VisualBasic.
- **Syntax-Highlighting für Python**: Python-Code wird farblich hervorgehoben.
- **Code-Speicherung in MySQL**: Die Code-Snippets werden direkt in einer MySQL-Datenbank gespeichert.
- **Intuitive GUI**: Benutzerfreundliches Interface mit modernen Buttons und Layouts.
- **Externe Website öffnen**: Ein Knopf ermöglicht das Öffnen einer vordefinierten Website.

## Voraussetzungen

Stelle sicher, dass die folgenden Software-Komponenten installiert sind:

1. **Python 3.x** (https://www.python.org/downloads/)
2. **MySQL** oder XAMPP für lokale Datenbankverwaltung (https://www.apachefriends.org/index.html)
3. **PyQt5** für die GUI
4. **MySQL-Connector für Python** zur Interaktion mit der MySQL-Datenbank

## Bilder

![Logo](Logo.png)



### Installation der Abhängigkeiten

```bash
pip install PyQt5
pip install mysql-connector-python
```

## Installation

1. Lade das Repository herunter oder klone es:

    ```bash
    git clone https://github.com/FrankyFriday/codeforge.git
    cd codeforge
    ```

2. Stelle sicher, dass dein MySQL-Server läuft und die Datenbank eingerichtet ist (siehe [Datenbankeinrichtung](#datenbankeinrichtung)).

3. Starte die Anwendung:

    ```bash
    python app.py
    ```

## Datenbankeinrichtung

1. Öffne dein MySQL-Management-Tool (z. B. über XAMPP oder MySQL Workbench).
2. Erstelle eine neue Datenbank mit dem Namen `codeforge`:

    ```sql
    CREATE DATABASE codeforge;
    ```

3. In der Datei `app.py`, passe die Datenbankverbindung in der Funktion `self.connection` an, um deinen MySQL-Benutzernamen und das Passwort korrekt anzugeben:

    ```python
    self.connection = mysql.connector.connect(
        host='127.0.0.1',
        user='root',  # dein MySQL-Benutzername
        password='',  # dein MySQL-Passwort
        database='codeforge'
    )
    ```

4. Die Anwendung erstellt automatisch eine Tabelle `snippets`, wenn sie das erste Mal Code speichert.

## Verwendung

1. **Sprache wählen**: Wähle eine Programmiersprache aus der Dropdown-Liste.
2. **Code eingeben**: Tippe den Code in das Textfeld ein. Syntax-Highlighting wird für Python unterstützt.
3. **Speichern**: Klicke auf „Speichern“, um den Code in der MySQL-Datenbank abzulegen.
4. **Website öffnen**: Klicke auf „Zur Website“, um eine vordefinierte externe Website in deinem Standardbrowser zu öffnen.

### Benutzeroberfläche

- **Sprache**: Wähle eine Sprache, in der du deinen Code schreiben möchtest.
- **Code-Eingabe**: Das Hauptfenster erlaubt es dir, den Code zu schreiben und anzuzeigen.
- **Speichern**: Speichert den eingegebenen Code in der Datenbank.
- **Zur Website**: Öffnet eine vordefinierte Website.

## Code-Struktur

- `app.py`: Hauptskript, das die gesamte Logik der Anwendung und das Layout der Benutzeroberfläche enthält.
- **PythonHighlighter**: Diese Klasse implementiert das Syntax-Highlighting für Python-Code.
- **MainWindow**: Diese Klasse stellt das Hauptfenster der Anwendung dar und verwaltet das Layout, die Datenbankinteraktionen und Benutzeraktionen.

## Fehlersuche

### Fehler 1: `Access denied for user 'root'@'localhost'`

- Dieser Fehler tritt auf, wenn falsche MySQL-Zugangsdaten verwendet werden. Stelle sicher, dass Benutzername und Passwort in der Datei `app.py` korrekt angegeben sind.

### Fehler 2: `Could not connect to MySQL server`

- Überprüfe, ob der MySQL-Server läuft. Falls du XAMPP verwendest, stelle sicher, dass MySQL gestartet ist. Überprüfe auch, ob der Port korrekt ist (standardmäßig `3306`).

### Fehler 3: `No module named 'PyQt5'`

- Dieser Fehler weist darauf hin, dass PyQt5 nicht installiert ist. Installiere es mit folgendem Befehl:

    ```bash
    pip install PyQt5
    ```

### Fehler 4: `NameError: name 'QHBoxLayout' is not defined`

- Dies kann auftreten, wenn `QHBoxLayout` oder eine andere Layout-Klasse nicht richtig importiert wurde. Achte darauf, dass die notwendigen PyQt5-Layouts korrekt in deinem Code verwendet werden.

## Lizenz

Dieses Projekt steht unter der MIT-Lizenz. Weitere Informationen findest du in der [LICENSE](LICENSE)-Datei.

## Kontakt

Für Fragen oder weitere Informationen:

- Entwickler: Franky_Friday GitHub
