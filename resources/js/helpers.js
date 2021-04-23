window.getCookie = function (name) {
    var match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
    if (match) return match[2];
}

window.replaceRange = function (s, start, end, substitute) {
    return s.substring(0, start) + substitute + s.substring(end);
}