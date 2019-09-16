import { Component, EventEmitter, Input, Output } from '@angular/core';
import { Client } from '../../../services/api/client';
import { Session } from '../../../services/session';

@Component({
  selector: 'm-modal--tos-updated',
  templateUrl: 'tos.component.html'
})

export class TOSUpdatedModal {

  user = window.Opspot.user;
  site_url = window.Opspot.site_url;
  showModal: boolean = false;

  constructor(private client: Client, private session: Session) { }

  ngOnInit() {
    if (this.session.getLoggedInUser().last_accepted_tos < window.Opspot.last_tos_update) {
      this.showModal = true;
    }
  }

  async updateUser() {
    try {
      const response: any = await this.client.post('api/v2/settings/tos');
      this.user.last_accepted_tos = response.timestamp;
    } catch (e) {
      console.error(e);
    }
  }

  close(e) {
    this.updateUser();
    this.showModal = false; 
  }

}

