Przeobrażanie fotografii (Morphing)
-----------------------------------

Często mamy do czynienia z przezentacją fotografii przezentującej obiekty
tego samego typu np. twarze ludzi, samochody lub budynki. Aby wywołać przyjemny
dla oka efekt, stosujemy różne sposoby prezentacji kolejnej fotografii w danym pokazie.
Jednym z takich sposobów jest płynne przejście jednego obrazu w drugi
polegające na stopniowym przenikaniu obrazu docelowego (następnego)  przez obraz źródłowy (obecnie wyświetlany).
Można to robić na dwa sposoby:

    * Mieszajać wartości barwnych kanałów (RGB) obrazu źródłowego i docelowego
    * Używając kanał alpha (w przypadku gdy możemy zastosować 4 kanałowe wartości koloru RGBA 32bit)

Dalej będziemy się odwoływać do pierwszego sposobu, ponieważ stosowanie RGB jest o wiele bardziej rozpowszechnione niż RGBA.

Ogólnie
=======

Przenikanie, niezależnie od sposobu, wymaga ustalenia skończonej liczby kroków, gdzie krok 0 oznaczał będzie
przezentację wyłącznie obrazu źródłowego natomiast krok ostatni wyłącznie obrazu docelowego.
Dla uproszczenia przyjmiemy wartość t, która będzie oznaczać poziom widoczności obrazu docelowego (target) w stosunku
do obrazu źródłowego. t może przyjmować wartości z przedziału [0, 1], gdzie 0 oznacza zupełny brak
widoczności obrazu docelowego natomiast 1 wyświetlenie wyłącznie obrazu docelowego.



.. math::

    \begin{bmatrix}
        T_0(x_0) & T_1(x_0) & \cdots & T_n(x_0) \\
        T_0(x_1) & T_1(x_1) & \cdots & T_n(x_1) \\
        \vdots   & \vdots   & \ddots & \vdots   \\
        T_0(x_n) & T_1(x_n) & \cdots & T_n(x_n)
    \end{bmatrix}
        \cdot
    \begin{bmatrix}
        a_0    \\
        a_1    \\
        \vdots \\
        a_n
    \end{bmatrix}
        =
    \begin{bmatrix}
        y_0    \\
        y_1    \\
        \vdots \\
        y_n
    \end{bmatrix}

