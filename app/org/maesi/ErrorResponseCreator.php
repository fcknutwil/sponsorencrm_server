<?php
namespace org\maesi;


class ErrorResponseCreator
{
    public static function create($message = "Es ist ein unerwarteter Fehler aufgetreten") {
        return ["message" => $message];
    }
    public static function createNotFound() {
        return self::create("Datensatz nicht gefunden");
    }
    public static function createDuplicate($field) {
        return self::create("Das Feld '$field' muss eindeutig sein.");
    }
    public static function createRequiredIsMissing($field) {
        return self::create("Das Feld '$field' ist Pflicht.");
    }
}