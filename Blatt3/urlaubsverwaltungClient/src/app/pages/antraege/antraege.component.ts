import { Component, OnInit, ChangeDetectorRef } from '@angular/core';
import { Router }            from '@angular/router';
import { UrlaubsantragService } from 'src/app/services/UrlaubsantragService.service';
import { Urlaubsantrag } from 'src/app/models/Urlaubsantrag';
import * as moment from 'moment';
import { LoginService } from 'src/app/services/LoginService.service';
import { Benutzer } from 'src/app/models/Benutzer';
import { Mitarbeiter } from 'src/app/models/Mitarbeiter';

@Component({
    selector: 'app-antraege',
    templateUrl: './antraege.component.html',
    styleUrls: ['./antraege.component.css']
})
export class AntraegeComponent implements OnInit {
    public aktuellerAntrag: Urlaubsantrag;
    public bemerkung: string;
    public benutzer: Benutzer;
    public mitarbeiter: Mitarbeiter;
    public ueberstundentage: number;
    public urlaubstage_nehmen: number;
    public ueberstundentage_nehmen: number;

    public constructor(private router: Router, private speicherService: UrlaubsantragService, 
            private loginService: LoginService) {
        this.mitarbeiter = this.loginService.getAktuellerMitarbeiter();
        this.aktuellerAntrag = null;
        this.bemerkung = '';
        const heute = moment().format("YYYY-MM-DD");
    }

    public ngOnInit(): void {
        this.benutzer = this.loginService.getAktuellerBenutzer();
        if (this.benutzer == null) {
            this.router.navigate([ 'login' ]);
        }
    }

    public schliessen(): void {
        this.router.navigate(['login']);
    }

    /**
     * Berechnet die Anzahl Werktage zwischen von- und bis-Datum.
     * @param von 
     * @param bis 
     */
    public tage(von: string, bis: string): number {
        let vonMoment = moment(von);
        const bisMoment = moment(bis);
        let urlaubstage = 0;

        while (true) {
            const day = vonMoment.weekday();
            if (day > 0 && day < 6) { // 0 = Sonntag, 6 = Samstag
                urlaubstage++;
            }
            if (vonMoment.isSame(bisMoment)) 
                break;
            vonMoment.add(1, 'days');
        }

        return urlaubstage;
    }

    /**
     * Schliesst den Dialog zum Stellen eines Antrags.
     */
    public antragStellenDialogSchliessen(): void {
        document.getElementById('antrag-stellen-dialog-schliessen').click();
    }

    public antraege(status: string): Array<Urlaubsantrag> {
        let result = Array<Urlaubsantrag>();

        if (this.mitarbeiter != null && this.mitarbeiter.urlaubsantraege != null) {
            this.mitarbeiter.urlaubsantraege.forEach(antrag => {
                if (antrag.status == status || status == '')
                    result.push(antrag);
            });
            result.sort((a1, a2) => {
                const m1 = moment(a1.von);
                const m2 = moment(a2.von);

                if (m1.isSame(m2)) 
                    return 0;
                if (m1.isBefore(moment(m2))) 
                    return 1;
                return -1;
            });
        }

        return result;
    }

    public antragLoeschen(id: number): void {
        // TODO
    }

}
