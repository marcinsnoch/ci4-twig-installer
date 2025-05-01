const baseUrl = location.protocol + '//' + location.hostname + (location.port ? ':' + location.port : '');

// set menu active class
$(function () {
    const url = window.location;
    const menu = url.pathname.split("/");
    if (menu.length > 2) {
        $('.nav a[href="' + url.origin + '/' + menu[1] + '"]').addClass("active");
    } else {
        $('.nav a[href="' + url.href + '"]').addClass("active");
    }
});
