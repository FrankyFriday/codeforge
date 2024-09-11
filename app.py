import sys
import mysql.connector
from PyQt5.QtWidgets import QApplication, QMainWindow, QLabel, QPushButton, QTextEdit, QVBoxLayout, QWidget, QComboBox, QMessageBox, QFormLayout, QHBoxLayout
from PyQt5.QtGui import QFont, QColor, QSyntaxHighlighter, QTextCharFormat
from PyQt5.QtCore import Qt, QRegExp
from PyQt5.QtGui import QDesktopServices
from PyQt5.QtCore import QUrl

class PythonHighlighter(QSyntaxHighlighter):
    def __init__(self, document):
        super().__init__(document)

        self.highlight_rules = [
            (QRegExp(r"\bTrue\b|\bFalse\b"), Qt.darkBlue),
            (QRegExp(r"\bNone\b"), Qt.darkMagenta),
            (QRegExp(r"\bclass\b|\bdef\b"), Qt.darkCyan),
            (QRegExp(r"\band\b|\bor\b|\bnot\b"), Qt.darkRed),
            (QRegExp(r"\bin\b|\bis\b|\bglobal\b|\bnonlocal\b|\bimport\b"), Qt.darkGreen),
            (QRegExp(r"\bif\b|\belif\b|\belse\b|\bfor\b|\bwhile\b"), Qt.darkYellow),
            (QRegExp(r"\btry\b|\bexcept\b|\bfinally\b|\braise\b"), Qt.darkGray),
            (QRegExp(r"\bwith\b|\bas\b|\band\b|\bor\b|\bnot\b|\byield\b"), Qt.darkMagenta),
            (QRegExp(r"\breturn\b|\bcontinue\b|\bbreak\b|\bpass\b"), Qt.darkBlue),
            (QRegExp(r"\bassert\b"), Qt.darkRed),
            (QRegExp(r"\bfrom\b|\bimport\b"), Qt.darkGreen),
            (QRegExp(r"\blambda\b"), Qt.darkCyan),
            (QRegExp(r"#.*"), Qt.darkGray)
        ]

    def highlightBlock(self, text):
        for pattern, color in self.highlight_rules:
            expression = QRegExp(pattern)
            index = expression.indexIn(text)

            while index >= 0:
                length = expression.matchedLength()
                char_format = QTextCharFormat()
                char_format.setForeground(QColor(color))
                self.setFormat(index, length, char_format)
                index = expression.indexIn(text, index + length)


class MainWindow(QMainWindow):
    def __init__(self):
        super().__init__()

        self.setWindowTitle("CodeForge")
        self.setGeometry(100, 100, 800, 600)

        self.init_ui()

    def init_ui(self):
        font = QFont()
        font.setPointSize(12)

        central_widget = QWidget()
        self.setCentralWidget(central_widget)
        layout = QVBoxLayout(central_widget)

        self.label = QLabel("Willkommen bei CodeForge!")
        self.label.setFont(font)
        self.label.setAlignment(Qt.AlignCenter)
        layout.addWidget(self.label)

        form_layout = QFormLayout()
        layout.addLayout(form_layout)

        self.language_label = QLabel("Sprache:")
        self.language_combobox = QComboBox()
        self.language_combobox.setFont(font)
        self.language_combobox.addItems(["Python", "Java", "C++", "JavaScript", "HTML", "CSS", "PHP", "Rust", "C", "VisualBasic"])

        form_layout.addRow(self.language_label, self.language_combobox)

        self.code_label = QLabel("Code:")
        self.code_textedit = QTextEdit()
        self.code_textedit.setFont(font)
        self.highlighter = PythonHighlighter(self.code_textedit.document())

        form_layout.addRow(self.code_label, self.code_textedit)

        button_layout = QHBoxLayout()
        layout.addLayout(button_layout)

        self.save_button = QPushButton("Speichern")
        self.save_button.setFont(font)
        self.save_button.setStyleSheet("""
            background-color: #4CAF50;
            color: white;
            border-radius: 5px;
        """)
        self.save_button.clicked.connect(self.save_code)
        button_layout.addWidget(self.save_button)

        self.website_button = QPushButton("Zur Website")
        self.website_button.setFont(font)
        self.website_button.setStyleSheet("""
            background-color: #008CBA;
            color: white;
            border-radius: 5px;
        """)
        self.website_button.clicked.connect(self.open_website)
        button_layout.addWidget(self.website_button)

        # Verbindung zur MySQL-Datenbank herstellen
        self.connection = mysql.connector.connect(
            host='127.0.0.1',
            user='root',
            password='',
            database='codeforge'
        )

    def save_code(self):
        language = self.language_combobox.currentText()
        code = self.code_textedit.toPlainText()

        # Überprüfen, ob der Code-Editor leer ist
        if not code:
            QMessageBox.warning(self, "Warnung", "Bitte geben Sie Ihren Code ein.")
            return

        # Speichern des Codes in der Datenbank
        if self.save_to_database(language, code):
            QMessageBox.information(self, "Erfolg", "Code erfolgreich gespeichert!")
            self.code_textedit.clear()
        else:
            QMessageBox.critical(self, "Fehler", "Fehler beim Speichern des Codes!")

    def save_to_database(self, language, code):
        try:
            cursor = self.connection.cursor()
            cursor.execute("CREATE TABLE IF NOT EXISTS snippets (id INT AUTO_INCREMENT PRIMARY KEY, language VARCHAR(255), code TEXT)")
            cursor.execute("INSERT INTO snippets (language, code) VALUES (%s, %s)", (language, code))
            self.connection.commit()
            cursor.close()
            return True
        except mysql.connector.Error as e:
            print(f"Fehler: {e}")
            return False

    def open_website(self):
        url = QUrl("http://localhost/codeforge/index.php")
        QDesktopServices.openUrl(url)

    def closeEvent(self, event):
        self.connection.close()


if __name__ == "__main__":
    app = QApplication(sys.argv)
    window = MainWindow()
    window.show()
    sys.exit(app.exec_())
