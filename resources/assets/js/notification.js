// Pops out a Bootstrap 4 floating notification
// Requires JQuery

window.create_notification = function(message, container, status, timeout = 2000) {
    let alert = document.createElement('div');
    alert.classList.add('alert', `alert-${status}`);
    alert.textContent = message;
    container.appendChild(alert);
    
    window.setTimeout(function() {
        $(alert).fadeOut();
    }, timeout)
}