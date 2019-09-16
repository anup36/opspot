import { Component } from '@angular/core';
import { OpspotTitle } from '../../../services/ux/title';
import { Session } from '../../../services/session';

@Component({
  selector: 'm-mobile--marketing',
  templateUrl: 'marketing.component.html'
})

export class MobileMarketingComponent {

  opspot = window.Opspot;
  user;

  constructor(
    private title: OpspotTitle,
    private session: Session,
  ) {
    this.title.setTitle('Mobile');
  }

  ngOnInit() {
    this.user = this.session.getLoggedInUser();
  }

}
