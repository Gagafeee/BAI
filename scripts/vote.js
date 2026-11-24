/** Displays a successful action result with a Toastify notification. */
function display_success(message) {
    Toastify({
        text: message,
        duration: 3000,
        close: true,
        gravity: 'top',
        position: 'right',
        style: { background: '#2e7d32' }
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
        style: { background: '#c62828' }
    }).showToast();
}

/** Updates the vote count only when the server confirms the vote was accepted. */
function handleVoteResponse(response, voteCount) {
    if (!response.ok) {
        display_error(response.message);
        return;
    }

    const currentCount = Number(voteCount.textContent.trim());

    voteCount.textContent = String(currentCount + 1);
    display_success(response.message);
}


// Bind the catalogue document.
document.querySelector('.catalogue')?.addEventListener('click', async (event) => {
    const button = event.target.closest('.vote-button');
    if (!button || button.disabled) {
        return;
    }

    const card = button.closest('[data-idea-id]');
    const voteCount = card?.querySelector('.vote-count');
    if (!card || !voteCount) {
        console.error('Impossible de trouver les informations de vote de cette idée.');
        return;
    }

    button.disabled = true;
    try {
        const response = await actionManager.sendAction('actions/send_add_vote.php', {
            id: card.dataset.ideaId
        });
        handleVoteResponse(response, voteCount);
    } catch (error) {
        console.error('Impossible de traiter la réponse du vote.', error);
    } finally {
        button.disabled = false;
    }
});