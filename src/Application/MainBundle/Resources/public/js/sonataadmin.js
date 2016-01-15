var locales = {
    pl: 'Polski',
    en: 'English'
};

$(document).ready(function () {

// When the page has not title set, the page name is set as a title:

    $('form[action^="/admin/application/main/page/create?"]')
            .on('keyup', 'input[id$=_name]', function (e) {
                var self = $(this);
                var form = self.closest('form');
                var tit = form.find('input[id$=_title]');

                var name = self.val();
                var title = $(tit.get(0)).val();

                if (name.slice(0, title.length) === title || title.slice(0, name.length) === name) {
                    tit.val(name);
                }
            })
            ;

// Add locale switcher:

    var positions = '';

    for (var locale in locales) {

        var url = Routing.generate('locale.switch', {locale: locale});

        positions += '<li role="presentation">'
                + '<a role="menuitem" tabindex="-1" href="' + url + '">' + locales[locale] + '</a>'
                + '</li>';
    }

    var menu = ''
            + '<li class="dropdown locale-switch">'
            + '    <a href="#" data-toggle="dropdown" class="dropdown-toggle">'
            + '        <i class="fa fa-globe fa-    lg fa-fw"></i> <i class="fa fa-caret-down"></i>'
            + '    </a>'
            + '    <ul class="dropdown-menu">'
            + positions
            + '    </ul>'
            + '</li>'
            ;

    $('.navbar.navbar-static-top .navbar-custom-menu ul.nav.navbar-nav').prepend(menu);

});
