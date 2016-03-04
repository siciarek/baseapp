Notatki na różne tematy
----------------------

Struktura kursu
===============

Główna klasa ``Training`` zawiera kolekcję obiektów ``TrainingPart`` (parts)

.. code-block:: yaml

    Training:
        type: ~ # followup, randomAccess, sequence -- default sequence
        parts: []


Typy kursów ``Training.type``

followup:
    Uczestnik otrzymuje kolejne części kursu, po upłynięciu odpowiedniej ilości czasu, np. codziennie jeden wykład.

sequence:
    Uczestnik przechodzi kurs sekwencyjnie, nie jest możliwe przejście do dowolnej części treningu (poza pierwszą) bez uprzedniego przejścia przez poprzednie, istnieje możliwość cofnięcia się.

sequenceStrict:
    Uczestnik przechodzi kurs sekwencyjnie, nie jest możliwe przejście do dowolnej części treningu (poza pierwszą) bez uprzedniego przejścia przez poprzednie, nie ma możliwości cofnięcia się.

randomAccess:
    Uczestnik ma do dyspozycji spis treści i jest w stanie w dowolnym czasie przejść do dowolnej części kursu.




``TrainingPart`` posiada nast interfejs:

artifact - połączenie z artefaktem, np. dokumentem, plikiem wideo, audio, ankietą, lub referencją


``Survey`` element służący do wymiany informacji między uczestnikiem i trenerem np. testy, upload plików z wykonanymi zadaniami lub proste potwierdzenie, zapoznania się z materiałem.
