import {
  ChangeDetectorRef,
  Component,
  HostBinding, Input,
  OnInit,
  ViewChild,
} from '@angular/core';
import { JitsiConfig, VideoChatService } from './videochat.service';

declare const JitsiMeetExternalAPI: any;

@Component({
  selector: 'm-videochat',
  templateUrl: './videochat.component.html',
})
export class VideoChatComponent implements OnInit {

  opspot = window.Opspot;
  isActive$;
  isFullWidth$;

  @Input() configs: JitsiConfig;
  @HostBinding('class.is-active') isActive = false;
  // @HostBinding('class.j-meetings-meeting--full-width') isFullWidth = false;
  @ViewChild('meet') meet;

  constructor(
    private service: VideoChatService,
    private cd: ChangeDetectorRef,
  ) {
  }

  ngOnInit() {
    this.isActive$ = this.service.activate$.subscribe((configs: JitsiConfig) => {
      if (configs) {
        this.configs = configs;
        this.startJitsi();
      } else {
        this.isActive = false;
      }
      this.cd.markForCheck();
      this.cd.detectChanges();
    });
  }

  ngOnDestroy() {
    this.service.deactivate();
    this.isActive$.unsubscribe();
  }

  startJitsi() {
    this.isActive = true;
    this.cd.markForCheck();
    this.cd.detectChanges();
    const domain = 'meet.jit.si';

    const options = {
      roomName: this.configs.roomName,
      width: '100%',
      parentNode: this.meet.nativeElement,
      avatarUrl: `${this.opspot.cdn_url}icon/${this.opspot.user.guid}/large/${this.opspot.user.icontime}`,
      interfaceConfigOverwrite: {
        // filmStripOnly: true,
        DEFAULT_REMOTE_DISPLAY_NAME: this.configs.username,
        SHOW_JITSI_WATERMARK: false,
        JITSI_WATERMARK_LINK: '',
        SHOW_WATERMARK_FOR_GUESTS: false,
        APP_NAME: 'Opspot',

        TOOLBAR_BUTTONS: [

          // main toolbar
          'microphone', 'camera', 'desktop', 'fullscreen', 'fodeviceselection', 'hangup', 'tileview',
          // extended toolbar
          'settings',
          'raisehand',
          'invite',
          'livestreaming',
          'videoquality', 'filmstrip',
          'stats',
        ],
      },

    };
    const api = new JitsiMeetExternalAPI(domain, options);

    api.executeCommand('displayName', this.configs.username || 'Unknown Opspot User');
    api.executeCommand('avatarUrl', `${this.opspot.cdn_url}icon/${this.opspot.user.guid}/large/${this.opspot.user.icontime}`);

    api.on('videoConferenceLeft', () => {
      this.service.deactivate();
    });
  }

  end() {
    // this.service.isActive.next(false);
  }

}
