
<?php header('Access-Control-Allow-Origin: *'); ?>
<?php

function __autoload($class_name) {
    include $class_name . '.php';
}

$dao = new DAO();

/*
 * Service-Implementierungen
 *  Operation	                HTTP-Befehl	Pfad	                        Request Body	Reply	        Status-Werte
 *  findeKundeZuBenutzername    GET	        kunde/{name}	                - 	        	Kunde			200
 *  erzeugeBestellung	        POST	    bestellung	                    Bestellung	    -	            200
 *  aktualisiereBestellung	    PUT	        bestellung/{id}	                Bestellung	    -   	        200, 404
 *  loescheBestellung	        DELETE	    bestellung/{id}	                - 	            -	            200, 404
 *  ladePositionenZuBestellung	GET	        bestellung/{id}/positionen	    -	            Bestellposition[]	200, 404
 *  erzeugePosition	            POST	    bestellung/{id}/position	    Bestellposition	Bestellposition	200, 404
 *  aktualisierePosition	    PUT	        bestellung/{id}/position/{nr}	Bestellposition	- 	            200, 404
 *  loeschePosition	            DELETE	    bestellung/{id}/position/{nr}	- 	            - 	            200, 404
 */


$url = $_REQUEST['_url'];
$requestType = $_SERVER['REQUEST_METHOD'];
$body = file_get_contents('php://input'); 

// ungültige Anfrage
function badRequest($requestType, $url, $body) {
 	http_response_code(400);
    die('Ungültiger Request: '.$requestType.' '.$url.' '.$body);        
}


function getKundeZuBenutzername($benutzername) {
	global $dao;
	
	$res = $dao->findeKundeZuBenutzername($benutzername);
	if ($res === NULL) {
        http_response_code(404);
    } else {
		echo json_encode($res);
		http_response_code(200); // kein Fehler
	}
}

function getLiesDatei($datei) {
	global $dao;
	
	$res = $dao->liesDatei($datei);
	if ($res === NULL) {
        http_response_code(404);
    } else {
		echo json_encode($res);
		http_response_code(200); // kein Fehler
	}
}

function getKundeZuId($kundenId) {
	global $dao;
	
	$res = $dao->findeKundeZuId($kundenId);
	if ($res === NULL) {
        http_response_code(404);
    } else {
		echo json_encode($res);
		http_response_code(200); // kein Fehler
	}
}

function getWarenkorbZuKunde($kundenId) {
	global $dao;
	$res = $dao->findeWarenkorbZuKunde($kundenId);
	if ($res === NULL) {
        http_response_code(404);
    } else {
		echo json_encode($res);
		http_response_code(200); // kein Fehler
	}
}

function getArtikelZuId($id) {
	global $dao;
	
	$res = $dao->findeArtikelZuId($id);
	if ($res === NULL) {
        http_response_code(404);
    } else {
		echo json_encode($res);
		http_response_code(200); // kein Fehler
	}
}

function getAlleArtikel() {
	global $dao;
	
	$res = $dao->findeAlleArtikel();
	if ($res === NULL) {
        http_response_code(404);
    } else {
		echo json_encode($res);
		http_response_code(200); // kein Fehler
	}
}

function getAlleKunden() {
	global $dao;
	
	$res = $dao->findeAlleKunden();
	if ($res === NULL) {
        http_response_code(404);
    } else {
		echo json_encode($res);
		http_response_code(200); // kein Fehler
	}
}

function putSpeichereKorb($body) {
	global $dao;

    $result = $dao->speichereWarenkorb($body);

    if ($result === false) {
        http_response_code(404);     
    } else {
        http_response_code(200);     
    }
}


try {
	if ($requestType === "OPTIONS") {
		http_response_code(200);
        header("Access-Control-Allow-Methods: GET, POST, DELETE, PUT, OPTIONS");
        header("Access-Control-Allow-Headers: Pragma, Cache-Control");
	} else if ($url === '/bestellungen') {
	
	} else if (preg_match("/\/speichern\//", $url) && $requestType === 'PUT') {
		putSpeichereKorb($body);
	} else if (preg_match("/\/kunde\/[0-999]*\//", $url) && $requestType === 'GET') { 
		$parts = explode('/', $url);
		$kundenid = $parts[2];
		getKundeZuId($kundenid);
	} else if (preg_match("/\/kunde\/.*?/", $url) && $requestType === 'GET') { 
		$parts = explode('/', $url);
		$name = $parts[2];
		getKundeZuBenutzername($name);
	} else if (preg_match("/\/datei\/.*?/", $url) && $requestType === 'GET') { 
		$parts = explode('/', $url);
		$datei = $parts[2];
		getLiesDatei($datei);
	} else if (preg_match("/\/warenkorb\/[0-999]*\//", $url) && $requestType === 'GET') { 
		$parts = explode('/', $url);
		$kundenid = $parts[2];
		getWarenkorbZuKunde($kundenid);
	} else if (preg_match("/\/artikel\/[0-999]*\//", $url) && $requestType === 'GET') { 
		$parts = explode('/', $url);
		$id = $parts[2];
		getArtikelZuId($id);
	} else if (preg_match("/\/alleartikel\//", $url) && $requestType === 'GET') { 
		getAlleArtikel();
	} else if (preg_match("/\/kunden\//", $url) && $requestType === 'GET') { 
		getAlleKunden();
	}
	
	else {
		throw new Exception('bad request');
	}
} catch (Exception $e) {
	badRequest($requestType, $url, $body);
}

?>