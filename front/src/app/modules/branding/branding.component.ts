import { Component } from '@angular/core';
import { Router } from '@angular/router';

import { Navigation as NavigationService } from '../../services/navigation';
import { Session } from '../../services/session';
import { OpspotTitle } from '../../services/ux/title';
import { Client } from '../../services/api';
import { LoginReferrerService } from '../../services/login-referrer.service';

@Component({
  selector: 'm-branding',
  templateUrl: 'branding.component.html'
})

export class BrandingComponent {

  opspot = window.Opspot;

  constructor(
    public client: Client,
    public title: OpspotTitle,
  ) {
    this.title.setTitle('Branding');
  }

}
