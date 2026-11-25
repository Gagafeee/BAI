/** Displays a successful action result with a Toastify notification. */
function display_success(message) {
    Toastify({
        text: message,
        duration: 3000,
        close: true,
        gravity: 'top',
        position: 'right',
        style: {background: '#2e7d32'}
    }).showToast();
}

/** Displays a failed action result with a Toastify notification. */
function display_error(message) {
    Toastify({
        text: message,
        duration: 7000,
        close: true,
        gravity: 'top',
        position: 'right',
        stopOnFocus: true,
        style: {background: '#c62828'}
    }).showToast();
}