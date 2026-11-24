/**
 * This file is intended to send form actions asynchronously.
 * It will replace all default form action by custom AJAX async requests.
 */

/**
 * Build a request from a form `event`, run it and parse result.
 * @param event SubmitEvent The Submit event from the Form.
 * All requests parameters will be inferred from the form properties.
 * If not `method` is set on the HTML form, POST will be used.
 */
function sendForm(event) {
    // Infer request data
    const formElement = event.target;
    if (formElement == undefined) throw new Error("Cannot send the form: Cannot infer request data from the form: Cannot locate the Form HTML element from the provided event.");

    const targetPath = formElement.action;
    if (targetPath == undefined || targetPath.length === 0) throw new Error("Cannot send the form: Cannot infer the request taget path from the form element. \n Set an 'action' attribute to the form element.");

    const method = (formElement.method ?? "POST");
    if (!["GET", "POST"].includes(method)) throw new Error(`Cannot send the form : Method ${method} is not valid : Should be GET or POST.`)

    // Get form data
    const data = new FormData(formElement);

    return sendActionRequest(targetPath, data, method);
}

function sendActionRequest(path, data, method = "POST") {
    const request = {
        body: data instanceof FormData ? data : ObjectToFormData(data),
        method: method
    };

    //TODO: notification_displayer?.clearAllError();

    return new Promise((resolve) => {
        try {
            // Send
            fetch(path, request)
                .then(async response => {
                    if (!response.ok) {
                        const data = await parse_response_data(response);
                        const message = data?.message;
                        display_error(new Error(message));
                    } else {
                        const data = await parse_response_data(response);
                        let message = data?.message ?? "L'action a été transmise correctement.";

                        // If request was redirected, redirect user to the specified location.
                        if (response.redirected) {
                            setTimeout(async () => {
                                const location_path = response.url;
                                window.location.assign(location_path);
                            }, 3000);
                            message += " Redirection...";
                        }
                        display_success(message);
                        resolve(data);
                    }
                })
                .catch(error => {
                    display_error(error);
                })
        } catch (error) {
            display_error(error);
        }
    });
}

const display_error = (error) => {
    const message = (error instanceof Error) ? error.message : error;
    //TODO: notification_displayer.notify(NOTIFICATION_TYPE.error, message)
    console.error(message);
}

const display_success = (message) => {
    message = message ?? "L'action a été transmise correctement.";
    //TODO: notification_displayer.notify(NOTIFICATION_TYPE.success, message);
    console.info(message);
}

async function parse_response_data(response) {
    try {
        return await response.json();
    } catch (e) {
        return null;
    }
}

/**
 * Convert an object `object` to FormData by appending all keys/values.
 * @param object
 * @returns {FormData}
 * @constructor
 */
function ObjectToFormData(object) {
    const form_data = new FormData();

    for (const key in object) {
        form_data.append(key, object[key]);
    }
    return form_data;
}

// Script to auto-bind all form on the page.
/*
document.addEventListener('submit', (event) => {
    event.preventDefault();

    const finishedCallback = event.target.getAttribute("whenFinished");

    sendForm(event, finishedCallback);
});*/
