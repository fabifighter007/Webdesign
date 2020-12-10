import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { Benutzer } from '../../models/Benutzer';
import { WebshopServer } from '../../services/webshopServer.service';

@Component({
    selector: 'app-login',
    templateUrl: './login.component.html',
    styleUrls: ['./login.component.css']
})

export class LoginComponent implements OnInit {
    public benutzer: Benutzer;
    public fehlermeldung: string;

    public constructor(public server: WebshopServer, public router: Router) {
    }

  public ngOnInit(): void {
      this.benutzer = new Benutzer('Hugo', '123');
      this.server.initKunden();
    }

    public login(): void {
        if (this.server.login(this.benutzer)) {
            this.router.navigate(['/artikelliste']);
        } else {
            this.fehlermeldung = 'Ungültige Benutzername/Passwort-Kombination!';
        }
    }

    public keyup(): void {
        this.fehlermeldung = '';
    }
}
