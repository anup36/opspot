import { ChangeDetectorRef, Component, NgZone } from '@angular/core';

import { NotificationService } from './modules/notifications/notification.service';
import { AnalyticsService } from './services/analytics';
import { SocketsService } from './services/sockets';
import { Session } from './services/session';
import { LoginReferrerService } from './services/login-referrer.service';
import { ScrollToTopService } from './services/scroll-to-top.service';
import { ContextService } from './services/context.service';
import { BlockchainService } from './modules/blockchain/blockchain.service';
import { Web3WalletService } from './modules/blockchain/web3-wallet.service';
import { Client } from './services/api/client';
import { WebtorrentService } from './modules/webtorrent/webtorrent.service';
import { ActivatedRoute, Router } from "@angular/router";
import { ChannelOnboardingService } from "./modules/onboarding/channel/onboarding.service";

@Component({
  moduleId: module.id,
  selector: 'm-app',
  templateUrl: 'app.component.html',
})
export class Opspot {
  name: string;
  opspot = window.Opspot;

  showOnboarding: boolean = false;

  showTOSModal: boolean = false;

  paramsSubscription;

  constructor(
    public session: Session,
    public route: ActivatedRoute,
    public notificationService: NotificationService,
    public scrollToTop: ScrollToTopService,
    public analytics: AnalyticsService,
    public sockets: SocketsService,
    public loginReferrer: LoginReferrerService,
    public context: ContextService,
    public web3Wallet: Web3WalletService,
    public client: Client,
    public webtorrent: WebtorrentService,
    public onboardingService: ChannelOnboardingService,
    public router: Router,
  ) {
    this.name = 'Opspot';
  }

  async ngOnInit() {
    this.notificationService.getNotifications();

    this.session.isLoggedIn(async (is) => {
      if (is) {
        this.showOnboarding = await this.onboardingService.showModal();
        if (this.opspot.user.language !== this.opspot.language) {
          console.log('[app]:: language change', this.opspot.user.language, this.opspot.language);
          window.location.reload(true);
        }
      }
    });

    this.onboardingService.onClose.subscribe(() => {
      this.showOnboarding = false;
      this.router.navigate(['/newsfeed']);
    });

    this.onboardingService.onOpen.subscribe(async () => {
      this.showOnboarding = await this.onboardingService.showModal(true);
    });

    this.loginReferrer
      .avoid([
        '/login',
        '/logout',
        '/logout/all',
        '/register',
        '/forgot-password',
      ])
      .listen();

    this.scrollToTop.listen();

    this.context.listen();

    this.web3Wallet.setUp();

    this.webtorrent.setUp();
  }

  ngOnDestroy() {
    this.loginReferrer.unlisten();
    this.scrollToTop.unlisten();
    this.paramsSubscription.unsubscribe();
  }
}
