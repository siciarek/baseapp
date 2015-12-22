$(document).ready(function () {
    Spinner.selector = $('body > div.spinner');
    Window.selector = $('body > div.modal.window');
    Dialog.selector = $('body > div.dialog.template')
            .on('click', '.save,.yes,.no, .ok', function (e) {
                var form = $('body > div.dialog.template form.form-horizontal');
                var control = form.find('input[name=text]');

                if ($(this).hasClass('yes')) {
                    control.val(1);
                }

                if ($(this).hasClass('no')) {
                    control.val(0);
                }

                if ($(this).hasClass('ok')) {
                    return Dialog.callback();
                }

                form.find('.submit').trigger('click');
            })
            ;

    $('body > div.dialog.template form.form-horizontal')
            .submit(function (e) {
                e.preventDefault();

                var value = $(this).closest('.modal').find('.modal-body input[name="text"]').val().trim();

                value = value.length === 0 ? null : value;

                Dialog.callback(value);
                Dialog.callback = Dialog.defaultCallback;

                $(this).closest('.modal').modal('hide');
            });
});

var Window = {
    selector: null,
    show: function (title, content, width) {

        title = title || null;
        content = content || null;
        width = width || 'w75';

        var win = this.selector;

        if (title !== null) {
            win.find('.modal-header .title').html(title);
        }

        $(['w100', 'w75', 'w50']).each(function (i, e) {
            win.find('.modal-dialog').removeClass(e);
        });

        win.find('.modal-dialog').addClass(width);
        win.find('.modal-dialog .submit').addClass('hidden');

        win.find('.modal-body').html('');

        if (content !== null) {
            win.find('.modal-body').html($(content).html());
        }

        win.modal();
    },
    form: function (title, content, callback, width) {

        title = title || null;
        content = content || null;
        callback = callback || null;
        width = width || 'w75';

        var win = this.selector;

        if (title !== null) {
            win.find('.modal-header .title').html(title);
        }

        win
                .on('click', '.submit', function (e) {
                    win.find('form').find('*[type=submit]').trigger('click');
                })
        if (callback !== null) {
            win
                    .on('submit', 'form', function (e) {
                        e.preventDefault();
                        Spinner.show();
                        callback(win);

                        $(this).closest('.modal').modal('hide');
                    });
        } else {
            win
                    .on('submit', 'form', function (e) {
                        Spinner.show();
                        $(this).closest('.modal').modal('hide');
                    });

        }

        $(['w100', 'w75', 'w50']).each(function (i, e) {
            win.find('.modal-dialog').removeClass(e);
        });

        win.find('.modal-dialog').addClass(width);
        win.find('.modal-dialog .submit').removeClass('hidden');

        win.find('.modal-body').html('');

        if (content !== null) {
            $(content).find('*[type=submit]').addClass('hidden');
            var body = win.find('.modal-body').html($(content).html());
        }

        win.modal();
    }
};

var Spinner = {
    selector: null,
    show: function () {
        this.selector.removeClass('hidden');
    },
    hide: function () {
        this.selector.addClass('hidden');
    }
};

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
 * Dialog.input('Podaj swoje imię:')
 * Dialog.question('Czy jesteś pełnoletni?');
 * Dialog.confirmation('Czy na pewno chcesz usunąć tę pozycję?');
 *
 * Zmiana domyślnych tytułów okien:
 *
 * Dialog.setTypes({
 *    error: 'Błąd',
 *    warning: 'Ostrzeżenie',
 *    info: 'Informacja',
 *    input: 'Wprowadź dane',
 *    question: 'Pytanie',
 *    confirmation: 'Potwierdzenie'
 * });
 *
 * @type {{setTypes: setTypes, types: {error: string, warning: string, info: string, question: string, confirmation: string}, dialog: dialog, error: error, warning: warning, question: question, info: info, confirmation: confirmation}}
 */
var Dialog = {
    selector: null,
    value: null,
    val: function () {
        return this.value;
    },
    defaultCallback: function (val) {

    },
    callback: function (val) {

    },
    /**
     * Metoda do zmiany domyślnych tytułów okien dialogowych, np. w innych językach.
     *
     * @param types obiekt
     */
    setTypes: function (types) {
        for (var key in types) {
            if (this.types.hasOwnProperty(key)) {
                this.types[key] = types[key];
            }
        }
    },
    types: {
        error: 'Error',
        warning: 'Warning',
        info: 'Information',
        input: 'Input',
        question: 'Question',
        confirmation: 'Confirmation'
    },
    icons: {
        error: 'fa-times-circle',
        warning: 'fa-exclamation-circle',
        info: 'fa-info-circle',
        input: 'fa-edit',
        question: 'fa-question-circle',
        confirmation: 'fa-check-square-o'
    },
    buttons: {
        error: ['ok'],
        warning: ['ok'],
        info: ['ok'],
        input: ['save', 'cancel'],
        question: ['yes', 'no'],
        confirmation: ['yes', 'no']
    },
    dialog: function (type, message, title, callback) {
        title = title || this.types[type];
        callback = callback || this.callback;
        message = message || title;
        var icon = this.icons[type];

        var dialog = this.selector;

        if (dialog) {
            dialog.find('.modal-title .title').html(title);
            dialog.find('.modal-body .message').html(message);

            for (var t in this.types) {
                if (this.types.hasOwnProperty(t)) {
                    dialog.find('i').removeClass(this.icons[t]).removeClass(t);
                }
            }

            dialog.find('form').get(0).reset();
            dialog.find('.modal-header i').addClass(icon);
            dialog.find('.modal-body i').addClass(icon).addClass(type);

            dialog.find('.modal-footer .btn').each(function (i, e) {
                $(e).addClass('hidden');
            });

            dialog.find('input[name=text]').addClass('hidden');

            if (type == 'input') {
                Dialog.value = null;
                dialog.find('input[name=text]').removeClass('hidden');
            }

            $(this.buttons[type]).each(function (i, e) {
                var btn = dialog.find('.modal-footer .btn.' + e);
                btn.removeClass('hidden');
            });

            Dialog.callback = callback;
        }

        return dialog.modal();
    },
    /**
     * Okienko dialogowe błędu
     *
     * @param message
     * @param title tytuł (parametr opcjonalny)
     * @param callback funkcja wykonywana po zamknięciu okienka
     */
    error: function (message, title, callback) {
        callback = callback || null;
        return this.dialog('error', message, title, callback);
    },
    /**
     * Okienko dialogowe informacyjne
     *
     * @param message
     * @param title tytuł (parametr opcjonalny)
     * @param callback funkcja wykonywana po zamknięciu okienka
     */
    info: function (message, title, callback) {
        message = message || 'OK';
        callback = callback || null;
        return this.dialog('info', message, title, callback);
    },
    /**
     * Okienko dialogowe ostrzeżenia
     *
     * @param message
     * @param title tytuł (parametr opcjonalny)
     * @param callback funkcja wykonywana po zamknięciu okienka
     */
    warning: function (message, title, callback) {
        callback = callback || null;
        return this.dialog('warning', message, title, callback);
    },
    /**
     * Okienko dialogowe wprowadzenia danych
     *
     * @param message
     * @param title tytuł (parametr opcjonalny)
     * @param callback funkcja wykonywana po zamknięciu okienka
     */
    input: function (message, title, callback) {
        callback = callback || function (value) {
            console.log([title, message, value]);
        };
        return this.dialog('input', message, title, callback);
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
        title = title || this.types.question;
        callback = callback || function (val) {
            console.log([title, message, val]);
            return true;
        };
        return this.dialog('question', message, title, callback);
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
        title = title || this.types.confirmation;
        callback = callback || function (val) {
            console.log([title, message, val]);
            return true;
        };
        return this.dialog('confirmation', message, title, callback);
    }
};
