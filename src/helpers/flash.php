<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function setFlashMessage($type, $message)
{
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message,
    ];
}
