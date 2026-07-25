<?php

/** Lightweight CSRF-token provider for adminmaster forms and AJAX requests. */
class security {
    public static function getToken(): string {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['csrf_token'];
    }
}
