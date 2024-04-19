function showPopup(message, isSuccess) {
    var popup = document.createElement('div');
    popup.className = isSuccess == true ? 'popup success' : 'popup error';
    popup.innerHTML = '<p>' + message + '</p>';
    document.body.appendChild(popup);

    popup.style.top = '-60px';
    setTimeout(function() {
        popup.style.top = '0';
    }, 100);

    setTimeout(function() {
        popup.style.top = '-50px';

        setTimeout(function() {
            popup.remove();
        }, 500);
    }, 3000);
}