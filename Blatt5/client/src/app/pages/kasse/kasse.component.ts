import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router'; 

import { WebshopServer } from '../../services/webshopServer.service';
import { Kunde } from '../../models/Kunde';
import { Warenkorb } from '../../models/Warenkorb';
import { WarenkorbPosition } from '../../models/WarenkorbPosition';
import { ArtikelService } from 'src/app/services/artikelService.service';

@Component({
    selector: 'app-kasse',
    templateUrl: './kasse.component.html',
    styleUrls: ['./kasse.component.css']
})

/**
 * Daten und Logik für die Kasse-Komponente, d.h. das Template "kasse.component.html".
 * Hinweis: Diese Komponente ist eine vereinfachte Version der Warenkorb-Komponente.
 */
export class KasseComponent implements OnInit {
    
    public kunde: Kunde;
    public warenkorb: Warenkorb;
    public nachricht: string;
    

    public constructor(private server: WebshopServer, private artikelService: ArtikelService, 
            private router: Router) {
    }

    public ngOnInit(): void {
        
        this.nachricht = '';
      this.kunde = this.server.aktuellerKunde;
      this.server.ladeWarenkorbZuKunde(this.kunde.id).then(res => {
        this.warenkorb = res;
      });
        
    }

    
    public bezahlen(): void {
        this.warenkorb.status = 'bezahlt';
        this.server.speichereWarenkorb(this.warenkorb);
        this.nachricht = 'Danke! Wir versenden umgehend!';
        setTimeout(() => {
            this.nachricht = '';
            this.router.navigate(['warenkorb']);
        }, 3000);
    }

    public preisZuPosition(p: WarenkorbPosition): number {
        return this.artikelService.artikelPreis(p.artikelId) * p.mengeNeu;
    }

    public gesamtpreis(): number {
        let gesamt = 0;

        for (let pos of this.warenkorb.positionen) {
            gesamt += this.artikelService.artikelPreis(pos.artikelId) * pos.mengeNeu;
        }

        return gesamt;
    }
    
}
