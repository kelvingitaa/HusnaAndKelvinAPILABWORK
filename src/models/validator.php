<?php
class Validator {
    public static function sanitize($data) {
        return trim(htmlspecialchars($data, ENT_QUOTES, 'UTF-8'));
    }

    public static function email($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    public static function required($value) {
        return isset($value) && $value !== '';
    }

    public static function minLen($value, $len) {
        return mb_strlen($value) >= $len;
    }
}
