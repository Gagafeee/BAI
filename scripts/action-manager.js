/** Sends POST actions and normalizes their JSON responses. */
class ActionManager {
    /**
     * Sends a URL-encoded POST request and returns a normalized result.
     * @param {string} url The action endpoint.
     * @param {Object<string, string|number>} [data={}] The request fields.
     * @returns {Promise<{ok: boolean, status: number|null, message: string}>} The request result.
     */
    async sendAction(url, data = {}) {
        let response;

        try {
            response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams(data)
            });
        } catch (error) {
            return {
                ok: false,
                status: null,
                message: 'Impossible de contacter le serveur (erreur réseau).'
            };
        }

        let payload;
        try {
            payload = await response.json();
        } catch (error) {
            return {
                ok: false,
                status: response.status,
                message: 'La réponse du serveur n’est pas un JSON valide.'
            };
        }

        if (
            payload === null ||
            typeof payload !== 'object' ||
            Array.isArray(payload) ||
            !Object.prototype.hasOwnProperty.call(payload, 'message') ||
            (payload.message !== null && typeof payload.message !== 'string')
        ) {
            return {
                ok: false,
                status: response.status,
                message: 'La réponse du serveur est invalide.'
            };
        }

        return {
            ok: response.ok,
            status: response.status,
            message: payload.message || (response.ok ? 'Action effectuée.' : `Erreur HTTP ${response.status}.`)
        };
    }
}

// Top-level class and const bindings are available to subsequent scripts.
const actionManager = new ActionManager();