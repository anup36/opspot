import { Component, ChangeDetectionStrategy, ChangeDetectorRef } from '@angular/core';

import { Client } from '../../common/api/client.service';

@Component({
  selector: 'm-wire--marketing',
  templateUrl: 'marketing.component.html',
  changeDetection: ChangeDetectionStrategy.OnPush
})

export class WireMarketingComponent {

  opspot = window.Opspot;
  user = window.Opspot.user;
  showSubscription: boolean = false;
  showVerify: boolean = false;

  constructor(private client: Client, private cd: ChangeDetectorRef) {
  }

  detectChanges() {
    this.cd.markForCheck();
    this.cd.detectChanges();
  }

}
