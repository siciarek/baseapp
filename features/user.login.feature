# language: pl

@all @login
Potrzeba biznesowa: Użytkownik powinien móc się zalogować
  Aby korzystać z systemu
  As a Użytkownik aplikacji
  Chciałbym mieć dostęp do aplikacji

  Założenia: Użytkownik powinien być wylogowany a strona powinna być w języku polskim i w trybie produkcyjnym
    Zakładając że odwiedzę stronę "/logout"
    I odwiedzę stronę "/locale/switch/pl"

  Scenariusz: Próba otwarcia aplikacji przez niezarejestrowanego użytkownika
    Zakładając że odwiedzę stronę "/private"
    Wtedy powinienem być na stronie "/login"
    I kod statusu odpowiedzi powinien być równy 200

  Scenariusz: Próba otwarcia aplikacji właściwym hasłem i loginem
    Zakładając że odwiedzę stronę "/private"
    Wtedy powinienem być na stronie "/login"
    I kod statusu odpowiedzi powinien być równy 200
    Jeżeli wypełnię pole "_username" wartością "colak"
    I wypełnię pole "_password" wartością "pass"
    I nacisnę przycisk "_submit"
    Wtedy powinienem być na stronie "/private"
    