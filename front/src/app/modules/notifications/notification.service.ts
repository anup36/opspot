import { EventEmitter } from '@angular/core';
import { Client } from '../../services/api';
import { SocketsService } from '../../services/sockets';
import { Session } from '../../services/session';
import { OpspotTitle } from '../../services/ux/title';

export class NotificationService {
  socketSubscriptions: any = {
    notification: null
  };
  onReceive: EventEmitter<any> = new EventEmitter();

  static _(session: Session, client: Client, sockets: SocketsService, title: OpspotTitle) {
    return new NotificationService(session, client, sockets, title);
  }

  constructor(public session: Session, public client: Client, public sockets: SocketsService, public title: OpspotTitle) {
    if (!window.Opspot.notifications_count)
      window.Opspot.notifications_count = 0;

    this.listen();
  }

  /**
   * Listen to socket events
   */
  listen() {
    this.socketSubscriptions.notification = this.sockets.subscribe('notification', (guid) => {
      this.increment();

      this.client.get(`api/v1/notifications/single/${guid}`)
        .then((response: any) => {
          if (response.notification) {
            this.onReceive.next(response.notification);
          }
        });
    });
  }

  /**
   * Increment the notifications counter
   */
  increment(notifications: number = 1) {
    window.Opspot.notifications_count = window.Opspot.notifications_count + notifications;
    this.sync();
  }

  /**
   * Clear the notifications. For notification controller
   */
  clear() {
    window.Opspot.notifications_count = 0;
    this.sync();
  }

  /**
   * Return the notifications
   */
  getNotifications() {
    var self = this;
    setInterval(function () {
      console.log('getting notifications');

      if (!self.session.isLoggedIn())
        return;

      if (!window.Opspot.notifications_count)
        window.Opspot.notifications_count = 0;

      self.client.get('api/v1/notifications/count', {})
        .then((response: any) => {
          window.Opspot.notifications_count = response.count;
          self.sync();
        });
    }, 60000);
  }

  /**
   * Sync Notifications to the topbar Counter
   */
  sync() {
    for (var i in window.Opspot.navigation.topbar) {
      if (window.Opspot.navigation.topbar[i].name === 'Notifications') {
        window.Opspot.navigation.topbar[i].extras.counter = window.Opspot.notifications_count;
      }
    }
    this.title.setCounter(window.Opspot.notifications_count);

  }

}
