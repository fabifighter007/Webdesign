import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Urlaubsantrag } from '../models/Urlaubsantrag';
import { Mitarbeiter } from '../models/Mitarbeiter';

@Injectable()
export class UrlaubsantragService {
    // Achtung: '/rest' ist wichtig für die Umleitung an den Apache-Server!
    // der Rest des Pfades kann variieren...
    private uri = '/rest/urlaubsantragServer/';

    public constructor(private httpClient: HttpClient) {
    }

    public speichereUrlaubsantrag(antrag: Urlaubsantrag): Promise<number> {
        return this.httpClient.post(this.uri + 'urlaubsantrag', antrag)
            .toPromise()
            .then((id: number) => id)
            .catch(this.handleError);
    }

    public gibAntraege(mitarbeiterId: number): Promise<Array<Urlaubsantrag>> {
        return this.httpClient.get(this.uri + 'urlaubsantraege/' + mitarbeiterId)
            .toPromise()
            .then(response => {
                let antraege = Array<Urlaubsantrag>();
                const responseArray = response as Array<Urlaubsantrag>;

                for (let a of responseArray) {
                    antraege.push(new Urlaubsantrag(a.id, a.von, a.bis, 
                        a.mitarbeiterId, a.antragsteller, a.zeitstempel, 
                        a.status, a.bemerkung));
                }

                return antraege;
            })
            .catch(this.handleError);
    }

    public gibMitarbeiterZuId(mitarbeiterId: number): Promise<Mitarbeiter> {
        return this.httpClient.get(this.uri + 'mitarbeiter/' + mitarbeiterId)
            .toPromise()
            .then(response => {
                const mitarbeiter = response as Mitarbeiter;
                return mitarbeiter;
            })
            .catch(this.handleError);
    }


    public statusAendern(geaenderterAntrag: Urlaubsantrag, status: string): void {
        geaenderterAntrag.status = status;
        this.aktualisiereUrlaubsantrag(geaenderterAntrag);
    }

    public aktualisiereUrlaubsantrag(antrag: Urlaubsantrag): Promise<void> {
        return this.httpClient.put(this.uri + 'urlaubsantrag/' + antrag.id, antrag)
            .toPromise()
            .then(() => { })
            .catch(this.handleError);
    }

    public loescheUrlaubsantrag(id: number): Promise<void> {
        return this.httpClient.delete(this.uri + 'urlaubsantrag/' + id)
            .toPromise()
            .then(() => { })
            .catch(this.handleError);
    }

    private handleError(error: any): Promise<any> {
        console.log('Interner Fehler: ' + error.message);
        return Promise.reject(error.message || error);
    }
}
