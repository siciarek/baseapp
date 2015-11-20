// Add locale switcher

$(document).ready(function(){

    var locales = {
        pl: 'Polski',
        en: 'English'
    };

    var positions = '';

    for(var locale in locales) {

        var url = Routing.generate('locale.switch', { locale: locale });

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
