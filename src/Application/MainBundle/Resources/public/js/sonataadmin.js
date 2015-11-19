// Add locale switcher

$(document).ready(function(){
    
    var menu = ''
        + '<li class="dropdown locale-switch">'
        + '    <a href="#" data-toggle="dropdown" class="dropdown-toggle">'
        + '        <i class="fa fa-globe fa-lg fa-fw"></i> <i class="fa fa-caret-down"></i>'
        + '    </a>'
        + '    <ul class="dropdown-menu">'
        + '        <li role="presentation">'
        + '            <a role="menuitem" tabindex="-1" href="/locale/switch/pl">Polski</a>'
        + '        </li>'
        + '        <li role="presentation">'
        + '            <a role="menuitem" tabindex="-1" href="/locale/switch/en">English</a>'
        + '        </li>'
        + '    </ul>'
        + '</li>'
        ;

    $('.navbar.navbar-static-top .navbar-custom-menu ul.nav.navbar-nav').prepend(menu);
    
});
