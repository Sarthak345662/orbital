function orbital_expand_navbar() {

    var element = document.getElementById("search-navbar");

    if (element.classList.contains('expand-searchform')) {
        element.classList.remove("expand-searchform");
        return;
    } else {
        element.classList.add("expand-searchform");
        document.getElementById("search-input").focus();
    }

}