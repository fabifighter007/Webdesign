<?php 

/**
 * Data Access Object für Urlaubsanträge
 */
class DAO {
    private $mitarbeiter;

	public function __construct() {
        $datenJSON = file_get_contents("data.json");
        $arr = json_decode($datenJSON);
        $this->mitarbeiter = array();
        if ($arr !== NULL) {
            foreach ($arr as $key => $m) {
                $this->mitarbeiter[$m->id] = $m;
            }
        }
	}

    public function findeMitarbeiterZuId($id) {
        $ma = $this->mitarbeiter[$id];
        if ($ma !== NULL) {
            // Urlaubsanträge der MA ergänzen
            $ma->urlaubsantraegeMitarbeiter = $this->findeUrlaubsantraegeDerMitarbeiter($id);
        }
        return $ma;
    }

    public function findeBenutzerZuName($name) {
        foreach ($this->mitarbeiter as $id => $m) {
            if ($m->benutzer->name === $name) {
                return $m->benutzer;
            }
        }
        return NULL;
    }

    private function speichern() {
        $arr = array();
        foreach ($this->mitarbeiter as $ma) {
            $arr2 = array();
            foreach($ma->urlaubsantraege as $antrag) {
                $arr2[] = $antrag;
            }
            $ma->urlaubsantraege = $arr2;
            $arr[] = $ma;
        }
        file_put_contents("data.json", json_encode($arr));
    }

    public function neuerUrlaubsantragFuerMitarbeiter($antrag) {
        if (array_key_exists($antrag->mitarbeiterId, $this->mitarbeiter)) {
            $mitarbeiter = $this->mitarbeiter[$antrag->mitarbeiterId];
            $now = new DateTime();
            $antrag->id = $now->getTimestamp();
            $mitarbeiter->urlaubsantraege[] = $antrag;
            $this->speichern();
            return $antrag;
        }
        return NULL;
    }

    public function findeUrlaubsantraegeDerMitarbeiter($vorgesetzterId) {
        if (array_key_exists($vorgesetzterId, $this->mitarbeiter)) {
            $vorgesetzter = $this->mitarbeiter[$vorgesetzterId];
            $antraege = array();

            foreach ($vorgesetzter->mitarbeiter as $maId) {
                $m = $this->mitarbeiter[$maId];
                foreach ($m->urlaubsantraege as $a) {
                    $antraege[] = $a;
                }
            }

            return $antraege;
        }
        return NULL;
    }

    public function findeUrlaubsantraegeZuMitarbeiter($mitarbeiterId) {
        if (array_key_exists($mitarbeiterId, $this->mitarbeiter)) {
            $mitarbeiter = $this->mitarbeiter[$mitarbeiterId];
            $antraege = array();

            foreach ($mitarbeiter->mitarbeiter as $maId) {
                $m = $this->mitarbeiter[$maId];
                foreach ($m->urlaubsantraege as $a) {
                    $antraege[] = $a;
                }
            }

            return $antraege;
        }
        return NULL;
    }

    function aktualisiereUrlaubsantrag($antrag) {
        if (array_key_exists($antrag->mitarbeiterId, $this->mitarbeiter)) {
            $mitarbeiter = $this->mitarbeiter[$antrag->mitarbeiterId];
            foreach ($mitarbeiter->urlaubsantraege as $a) {
                if ($a->id === $antrag->id) {
                    $a->von = $antrag->von;
                    $a->bis = $antrag->bis;
                    $a->zeitstempel = $antrag->zeitstempel;
                    $a->status = $antrag->status;
                    $a->bemerkung = $antrag->bemerkung;
                    $this->speichern();
                    return $a;
                }
            }
        }
        return NULL;
    }

    function loescheUrlaubsantrag($id) {
        foreach ($this->mitarbeiter as $mitarbeiter) {
            $i = 0;
            foreach ($mitarbeiter->urlaubsantraege as $a) {
                if ($a->id == $id) {
                    unset($mitarbeiter->urlaubsantraege[$i]);
                    $this->speichern();
                    return $id;
                }
                $i++;
            }
        }
        return NULL;
    }
}
?>