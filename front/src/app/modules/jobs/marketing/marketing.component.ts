import { Component } from '@angular/core';
import { OpspotTitle } from '../../../services/ux/title';
import { Session } from '../../../services/session';

@Component({
  selector: 'm-jobs--marketing',
  templateUrl: 'marketing.component.html'
})

export class JobsMarketingComponent {

  opspot = window.Opspot;
  user;

  constructor(
    private title: OpspotTitle,
    private session: Session,
  ) {
    this.title.setTitle('Join the team');
  }

  ngOnInit() {
    this.user = this.session.getLoggedInUser();
  }

}
