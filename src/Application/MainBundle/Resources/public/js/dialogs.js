var Spinner = {
    selector: null,
    show: function() {
        this.selector.removeClass('hidden');
    },
    hide: function() {
        this.selector.addClass('hidden');
    }
};

$(document).ready(function() {
    Spinner.selector = $('body > div.spinner');    
});

/**
 * Obiekt zawierający typowe okienka dialogowe,
 * do komunikacji z użytkownikiem.
 * Na razie oparte na alertach i confirmach ale w planie
 * jest implementacja na behatowych modalach.
 *
 * Przykładowe użycie:
 *
 * Dialog.error('Wystąpił niespodziewany błąd.');
 * Dialog.warning('Nie znaleziono wyników wyszukiwania.');
 * Dialog.info('Dodanie użytkownika zakończyło się powodzeniem.');
 * Dialog.question('Czy jesteś pełnoletni?');
 * Dialog.confirmation('Czy na pewno chcesz usunąć tę pozycję?');
 *
 * Zmiana domyślnych tytułów okien:
 *
 * Dialog.setTypes({
 *    error: 'Błąd',
 *    warning: 'Ostrzeżenie',
 *    info: 'Informacja',
 *    question: 'Pytanie',
 *    confirmation: 'Potwierdzenie'
 * });
 *
 * @type {{setTypes: setTypes, types: {error: string, warning: string, info: string, question: string, confirmation: string}, dialog: dialog, error: error, warning: warning, question: question, info: info, confirmation: confirmation}}
 */
var Dialog = {
    /**
     * Metoda do zmiany domyślnych tytułów okien dialogowych, np. w innych językach.
     *
     * @param types obiekt
     */
    setTypes: function(types) {
        for(var key in types) {
            if(this.types.hasOwnProperty(key)) {
                this.types[key] = types[key];
            }
        }
    },
    types: {
        error: 'Error',
        warning: 'Warning',
        info: 'Information',
        question: 'Question',
        confirmation: 'Confirmation'
    },
    dialog: function (type, message, title, callback) {
        title = title || this.types[type];
        callback = callback || function () {};
        message = message || title;

        var msg = [title, '', '[' + type.toUpperCase() + ']', '', message].join("\n");

        if(type === 'confirmation' || type === 'question') {
            
            if(confirm(msg)) {
                callback();
                return true;
            }
            
            return false;
        }

        return alert(msg);
    },
    /**
     * Okienko dialogowe błędu
     *
     * @param message
     * @param title tytuł (parametr opcjonalny)
     * @param callback funkcja wykonywana po zamknięciu okienka
     */
    error: function (message, title, callback) {
        this.dialog('error', message, title);
    },
    /**
     * Okienko dialogowe informacyjne
     *
     * @param message
     * @param title tytuł (parametr opcjonalny)
     * @param callback funkcja wykonywana po zamknięciu okienka
     */
    info: function (message, title, callback) {
        this.dialog('info', message, title);
    },
    /**
     * Okienko dialogowe ostrzeżenia
     *
     * @param message
     * @param title tytuł (parametr opcjonalny)
     * @param callback funkcja wykonywana po zamknięciu okienka
     */
    warning: function (message, title, callback) {
        this.dialog('warning', message, title);
    },
    /**
     * Okienko dialogowe zapytania
     *
     * @param message
     * @param title tytuł (parametr opcjonalny)
     * @param callback funkcja wykonywana po wybraniu opcji zatwierdzającej (guzik TAK)
     */
    question: function (message, title, callback) {
        message = message || 'Are you sure?';
        title = title || 'Question';
        callback = callback || function() {
            console.log([title, message]);
            return true;
        };
        this.dialog('question', message, title, callback);
    },
    /**
     * Okienko dialogowe potwierdzenie
     *
     * @param message
     * @param title tytuł (parametr opcjonalny)
     * @param callback funkcja wykonywana po wybraniu opcji zatwierdzającej (guzik TAK)
     */
    confirmation: function (message, title, callback) {
        message = message || 'Are you sure';
        title = title || 'Confirmation';
        callback = callback || function() {
            console.log([title, message]);
            return true;
        };
        
        return this.dialog('confirmation', message, title, callback);
    }
};
