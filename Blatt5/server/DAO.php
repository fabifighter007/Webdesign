<?php 

/**
 * Data Access Object für den Warenkorb-Server
 * - Artikel in Datei "artikel.json"
 * - Kunden in Datei "kunde.json"
 * - Warenkörbe in Datei "warenkorb.json"
 */
class DAO {
    private $warenkoerbe;
    private $artikel;

	public function __construct() {
        $this->artikel = $this->liesDatei("artikel.json");
        $this->kunden = $this->liesDatei("kunde.json");
        $this->warenkoerbe = $this->liesDatei("warenkorb.json");
	}

    /**
     * Liest JSON-Daten aus $datei und liefert ein durch die "id" indiziertes Array der Datenobjekte. 
     */
    private function liesDatei($datei) {
        $result = array();
        $datenJSON = file_get_contents($datei);
        $arr = json_decode($datenJSON);
        if ($arr !== NULL) {
            foreach ($arr as $key => $value) {
                $result[$value->id] = $value;
            }
        }
        return $result;
    }

    /**
     * Liefert den Kunden zu 'benutzername' oder NULL, wenn der Name ungültig ist.
     */
    public function findeKundeZuBenutzername($benutzername) { //done
        foreach ($this->kunden as $kunde) {
            if ($kunde->benutzername === $benutzername) {
                return $kunde;
            }
        }
        return NULL; 
    }

    /**
     * Liefert den Kunden zu 'kundenId' oder NULL.
     */
    public function findeKundeZuId($kundenId) { //done
		return $this->kunden[$kundenId]; 
    }

    /**
     * Liefert den Warenkorb zu Kunde mit ID 'kundenId'.
     */
	public function findeWarenkorbZuKunde($kundenId) { //done
        foreach($this->warenkoerbe as $warenkorb) {
            if ($warenkorb->kundenId == $kundenId) {
                return $warenkorb;
            }
        }
		return NULL;
	}

    /**
     * Speichert den übergebenen Warenkorb.
     */
    public function speichereWarenkorb($warenkorb) {
		$neu = json_decode($warenkorb);
		$id = $neu -> {'id'};
        
        $this->warenkoerbe[$id] = $neu;
        file_put_contents('warenkorb.json', json_encode($this->warenkoerbe));
        return true;
		
		
		/*
		$neu = json_encode($warenkorb);
        $this->warenkoerbe[$warenkorb->id] = $warenkorb;
        file_put_contents("warenkorb.json", json_encode($this->warenkoerbe));
		*/
    }

    /**
     * Liefert den Artikel zu "artikelId" oder NULL.
     */
    public function findeArtikelZuId($artikelId) { //done
        return $this->artikel[$artikelId];
    }

    /**
     * Liefert die Artikel als Array.
     */
    public function findeAlleArtikel() {
        $result = array();
        foreach($this->artikel as $a) {
            $result[] = $a;
        }
        return $result;
    }
	
	public function findeAlleKunden() {
        $result = array();
        foreach($this->kunden as $a) {
            $result[] = $a;
        }
        return $result;
    }
}
?>