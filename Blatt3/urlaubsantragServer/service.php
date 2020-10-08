<?php
function __autoload($class_name) {
    include $class_name . '.php';
}

$dao = new DAO();

/*
 * Service-Implementierungen
 */

function login($benutzer) {
    global $dao;

    $gefunden = $dao->findeBenutzerZuName($benutzer->name);
    if ($gefunden === NULL || $gefunden->passwort !== $benutzer->passwort) {
        http_response_code(404); // Fehler
    } else {
        $gefunden->passwort = '';
        http_response_code(200); // kein Fehler
        echo json_encode($gefunden);
    }
}

function urlaubsantragAnlegen($body) {
    global $dao;

    $antrag = $dao->neuerUrlaubsantragFuerMitarbeiter($body);
    if ($antrag === NULL) {
        http_response_code(404); // Fehler
    } else {
        http_response_code(200); // kein Fehler
        echo json_encode($antrag->id);
    }
}

function gibMitarbeiterZuId($mitarbeiterId) {
    global $dao;

    $mitarbeiter = $dao->findeMitarbeiterZuId($mitarbeiterId);
    if ($mitarbeiter === NULL) {
        http_response_code(404); // Fehler
    } else {
        http_response_code(200); // kein Fehler
        $mitarbeiter->benutzer->passwort = '';
        echo json_encode($mitarbeiter);
    }
}

function gibUrlaubsantraegeDerMitarbeiter($vorgesetzterId) {
    global $dao;

    $antraege = $dao->findeUrlaubsantraegeDerMitarbeiter($vorgesetzterId);
    if ($antraege === NULL) {
        http_response_code(404); // Fehler
    } else {
        http_response_code(200); // kein Fehler
        echo json_encode($antraege);
    }
}

function gibUrlaubsantraegeZuMitarbeiter($mitarbeiterId) {
    global $dao;

    $antraege = $dao->findeUrlaubsantraegeZuMitarbeiter($mitarbeiterId);
    if ($antraege === NULL) {
        http_response_code(404); // Fehler
    } else {
        http_response_code(200); // kein Fehler
        echo json_encode($antraege);
    }
}

function aktualisiereUrlaubsantrag($antrag) {
    global $dao;

    $ergebnis = $dao->aktualisiereUrlaubsantrag($antrag);
    if ($ergebnis === NULL) {
        http_response_code(404); // Fehler
    } else {
        http_response_code(200); // kein Fehler
        echo json_encode($ergebnis);
    }
}

function loescheUrlaubsantrag($id) {
    global $dao;

    $ergebnis = $dao->loescheUrlaubsantrag($id);
    if ($ergebnis === NULL) {
        http_response_code(404); // Fehler
    } else {
        http_response_code(200); // kein Fehler
    }
}


/*
 * Hilfsfunktionen
 */

// sollte ein Cross Origin Resource Sharing (CORS) request als OPTION call kommen,
// wird einfach alles erlaubt...
// ruft man einen fremden Server auf, so schickt der Browser einen OPTIONS request vorweg,
// um die Erlaubnis zu erlangen, den eigentlichen Aufruf durchzuführen
function handleCORS($requestType, $url, $body) {
 	http_response_code(200);
    header("Access-Control-Allow-Methods: GET, POST, DELETE, PUT, OPTIONS");
    header("Access-Control-Allow-Headers: Pragma, Cache-Control");
    header("Access-Control-Allow-Origin: *");
}

// ungültige Anfrage
function badRequest($requestType, $url, $body) {
 	http_response_code(400);
    die('Ungültiger Request: '.$requestType.' '.$url.' '.$body);        
}

/**
 * Service Dispatcher: zerlegt die Anfrage und ruft die passende
 * Service-Implementierung auf
 */

/**
 * Schnittstelle
 * CMD      Pfad                    	Request     		Reply           Code
 * POST     login                   	User        		MAId            200
 * POST 	urlaubsantrag               UrlaubsAntrag	    UrlaubsantragId 200/404
 * GET      mitarbeiter/{MAId}          -                   Mitarbeiter     200/404
 * GET      urlaubsantraegeMA/{MAId}    -                   Urlaubsantrag[] 200
 * GET      urlaubsantraege/{MAId}      -                   Urlaubsantrag[] 200
 * PUT      urlaubsantrag/{id}          Urlaubsantrag       -               200/404
 * DELETE   urlaubsantrag/{id}          -                   -               200/404
 */
$url = $_REQUEST['_url'];
$requestType = $_SERVER['REQUEST_METHOD'];
//$headers = getallheaders();
$body = file_get_contents('php://input'); 

try {
    if ($requestType === "OPTIONS") { // CORS: OPTIONS request kommt vor eig. request
        handleCORS($requestType, $url, $body);
    } else if ($url === '/login') {
        if ($requestType === 'POST') {
            login(json_decode($body));
        } else {
            throw new Exception('bad request');
        }
    } else if ($url === '/urlaubsantrag' && $requestType === 'POST') {
        urlaubsantragAnlegen(json_decode($body));
    } else if (preg_match("/\/mitarbeiter\/[0-9]*/", $url) && $requestType === 'GET') {
        $parts = explode('/', $url);
        if (count($parts) !== 3) {
            throw new Exception('bad request');
        } else {
            $maId = $parts[2];
            gibMitarbeiterZuId($maId);
        }
    } else if (preg_match("/\/urlaubsantraegeMA\/[0-9]*/", $url) && $requestType === 'GET') {
        $parts = explode('/', $url);
        if (count($parts) !== 3) {
            throw new Exception('bad request');
        } else {
            $maId = $parts[2];
            gibUrlaubsantraegeDerMitarbeiter($maId);
        }
    } else if (preg_match("/\/urlaubsantraege\/[0-9]*/", $url) &&
        $requestType === 'GET') {
        $parts = explode('/', $url);
        if (count($parts) !== 3) {
            throw new Exception('bad request');
        } else {
            $maId = $parts[2];
            gibUrlaubsantraegeZuMitarbeiter($maId);
        }
    } else if (preg_match("/\/urlaubsantrag\/[0-9]*/", $url) &&
        $requestType === 'PUT') {
        $parts = explode('/', $url);
        if (count($parts) !== 3) {
            throw new Exception('bad request');
        } else {
            aktualisiereUrlaubsantrag(json_decode($body));
        }
    } else if (preg_match("/\/urlaubsantrag\/[0-9]*/", $url) &&
        $requestType === 'DELETE') {
        $parts = explode('/', $url);
        if (count($parts) !== 3) {
            throw new Exception('bad request');
        } else {
            $id = $parts[2];
            loescheUrlaubsantrag($id);
        }
    } else {
        throw new Exception('bad request');
    }
} catch (Exception $e) {
    badRequest($requestType, $url, $body);
}
