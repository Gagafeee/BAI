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


// Intercept url parameters
document.addEventListener("DOMContentLoaded", () => {
    const parameters = new URLSearchParams(document.location.search);
    if (parameters.has("status")) {
        try {

            const status = parseInt(parameters.get("status"));

            if (status <= 299) {
                let message = parameters.get("message").trim();
                if (message.length === 0) message = "L'opération a bien été traité.";
                display_success(message);
            } else {
                let error = parameters.get("error").trim();
                if (error.length === 0) error = "Une erreur est survenue.";
                display_error(error);
            }

        } catch (e) {
            console.error(e);
        }

        // Remove all parameters from URL
        // Source - https://stackoverflow.com/a/41061471
        // Posted by evanjmg, modified by community. See post 'Timeline' for change history
        // Retrieved 2026-11-25, License - CC BY-SA 4.0
        window.history.replaceState(null, '', window.location.pathname);
    }
});