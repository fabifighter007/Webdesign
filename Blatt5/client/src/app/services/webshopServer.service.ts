import { Injectable, OnInit } from '@angular/core';

import { Benutzer } from '../models/Benutzer';
import { Warenkorb } from '../models/Warenkorb';
import { Kunde } from '../models/Kunde';
import { Artikel } from '../models/Artikel';
import { HttpClient } from '@angular/common/http';


@Injectable()
/**
 * Schnittstellenservice für den Webshop-Server. Dieser stellt Operationen für Login, Artikeldaten und Warenkorbdaten zur Verfügung.
 * 
 * Diese Implementierung simuliert den Server, indem Arrays mit Kunden, Artikel und Warenkörbe gehalten werden.
 * Die Operationen des Service greifen auf die Arrays zu.
 */
export class WebshopServer implements OnInit {

  private uri = 'http://localhost:80/wt/Blatt5/';


    private kunden: Array<Kunde>;
    private artikel: Array<Artikel> = JSON.parse(
    `[
        {
            "id": 1,
            "kurzText": "Milch",
            "beschreibung": "1L H-Milch 3,5% Fett",
            "preis": "0.80"
        },
        {
            "id": 2,
            "kurzText": "Butter",
            "beschreibung": "250gr. Markenbutter",
            "preis": "1.80"
        },
        {
            "id": 3,
            "kurzText": "Essig",
            "beschreibung": "1L Balsamico-Essig",
            "preis": "2.80"
        }
    ]`);
    private warenkoerbe: Array<Warenkorb> = JSON.parse(
    `{
        "1": {
            "id": 1,
            "kundenId": 1,
            "status": "angelegt",
            "positionen": [
                {
                    "nr": 1,
                    "artikelId": 2,
                    "menge": 1
                },
                {
                    "nr": 2,
                    "artikelId": 1,
                    "menge": 8
                },
                {
                    "nr": 3,
                    "artikelId": 3,
                    "menge": 9
                }
            ]
        },
        "2": {
            "id": 2,
            "kundenId": 2,
            "status": "angelegt",
            "positionen": []
        }
    }`);

    public aktuellerKunde: Kunde;

  public constructor(private httpClient: HttpClient) {
    }
    ngOnInit(): void {
      
    }

    public login(benutzer: Benutzer): boolean {
        for (let kunde of this.kunden) {
            if (benutzer.name === kunde.benutzername && benutzer.passwort === kunde.passwort) {
                this.aktuellerKunde = kunde;
                return true;
            }
        }
        return false;
    }

  public initKunden() {
    this.httpClient.get(this.uri + 'kunden/')
      .toPromise()
      .then(response => {
        let kunden = Array<Kunde>();
        const responseArray = response as Array<Kunde>;

        for (let b of responseArray) {
          console.log(b);
          kunden.push(new Kunde(
            b.id,
            b.name,
            b.benutzername,
            b.passwort));
        }
        this.kunden = kunden;
        console.log(kunden);
      })
      .catch(this.handleError);
  }

  public ladeWarenkorbZuKunde(kundeId: number): Promise<Warenkorb> {
    return this.httpClient.get(this.uri + "warenkorb/" + kundeId + "/")
      .toPromise()
      .then(res => {
        const responseWarenkorb = res as Warenkorb;
        return responseWarenkorb;
      });
    }

    public ladeAlleArtikel(): Promise<Array<Artikel>> {
      return this.httpClient.get(this.uri + 'alleartikel/')
        .toPromise()
        .then(response => {
          let artikel = Array<Artikel>();
          const responseArray = response as Array<Artikel>;

          for (let b of responseArray) {
            console.log(b);
            artikel.push(new Artikel(
              b.id,
              b.kurzText,
              b.beschreibung,
              b.preis));
          }
          return artikel;
        })
        .catch(this.handleError);
      //return this.artikel;
    }

  public speichereWarenkorb(warenkorb: Warenkorb): Promise<void> {
        if (warenkorb.status == 'bezahlt') {
            warenkorb = new Warenkorb(Number(Date.now()), warenkorb.kundenId, 'angelegt', []);
    }
    return this.httpClient.put(this.uri + 'speichern/', warenkorb).toPromise()
        .then(() => {
          console.log("Warenkorb gespeichert!");
        })
      //this.warenkoerbe[warenkorb.kundenId] = warenkorb;
    }

  private handleError(error: any): Promise<any> {
    alert('Interner Fehler: ' + error.message);
    return Promise.reject(error.message || error);
  }
}
