// script.js

// Példa JavaScript funkciók
document.addEventListener('DOMContentLoaded', function() {
    // Példa: menüpontra kattintva változik a háttérszín
    var menuItems = document.querySelectorAll('nav ul li a');
    menuItems.forEach(function(item) {
        item.addEventListener('click', function() {
            this.style.backgroundColor = 'red';
        });
    });

    // Egyéb JavaScript funkciók ide
});
