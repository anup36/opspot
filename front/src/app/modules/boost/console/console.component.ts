import { Component } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';

import { OpspotTitle } from '../../../services/ux/title';

export type BoostConsoleType = 'newsfeed' | 'content' | 'offers' | 'publisher';
export type BoostConsoleFilter = 'create' | 'history' | 'earnings' | 'payouts' | 'settings' | 'inbox' | 'outbox';

@Component({
  moduleId: module.id,
  selector: 'm-boost-console',
  templateUrl: 'console.component.html'
})
export class BoostConsoleComponent {

  type: BoostConsoleType;
  opspot: Opspot = window.Opspot;

  constructor(private router: Router, private route: ActivatedRoute, public title: OpspotTitle) {
    this.title.setTitle('Boost Console');
  }

  ngOnInit() {
  
    this.route.firstChild.url.subscribe(segments => {
      console.log(segments);
      this.type = <BoostConsoleType>segments[0].path;
    });

  }
}
