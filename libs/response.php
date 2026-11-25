<?php

/**
 * Redirect the current request to the provided `$location` at the end of the execution.
 * @param string $location The **relative** location to redirect to.
 */
function set_redirect(string $location): void
{
    header('Location: ' . "/" . $location);
}

/**
 * Redirect the current request to the provided `$location` with an 'ok' status and a custom `$message` at the end of the execution.
 * @param string $location The **relative** location to redirect to.
 */
function set_redirect_successful(string $location, string $message = ''): void
{
    $path = "/" . $location . "?status=200" . ($message !== '' ? "&message=" . rawurlencode($message) : "");
    header('Location: ' . $path);
}

/**
 * Redirect the current request to the provided `$location` with an 'error' status at the end of the execution.
 * @param int $error_code The custom HTTP code of the error.
 * @param string $error The error message to transfer BE CAUTIOUS NOT TO PROVIDE THE DIRECT ERROR FOR SECURITY REASONS.
 * @param string $location
 * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Reference/Status. The **relative** location to redirect to.
 */
function set_redirect_error(string $location, int $error_code = 500, string $error = ''): void
{
    $path = "/" . $location . "?status=" . rawurlencode($error_code) . ($error !== '' ? "&error=" . rawurlencode($error) : "");
    header('Location: ' . $path);
}

function respond_success(?string $message): void
{
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['message' => htmlspecialchars($message)]);
}

/**
 * @param array $content
 * @return void
 * WARNING: This function allow XSS injections, ALWAYS MAKE SURE that the provided content has correctly been escaped.
 */
function respond_success_with_content(array $content): void
{
    header('Content-Type: application/json; charset=utf-8');
    # JSON_UNESCAPED_SLASHES Is used not to break HTML content. https://stackoverflow.com/a/5064590
    echo json_encode($content, JSON_UNESCAPED_SLASHES);
}


function respond_error(?int $status = 500, ?string $message = "Une erreur est survenue"): void
{
    header('Content-Type: application/json; charset=utf-8');
    http_response_code($status);
    echo json_encode(['message' => htmlspecialchars($message)]);
    die();
}

