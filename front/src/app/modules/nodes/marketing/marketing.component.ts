import { Component } from '@angular/core';
import { OpspotTitle } from '../../../services/ux/title';

@Component({
  selector: 'm-nodes--marketing',
  templateUrl: 'marketing.component.html'
})

export class NodesMarketingComponent {

  opspot = window.Opspot;

  constructor(private title: OpspotTitle) {
    this.title.setTitle('Nodes');
  }

}
