import { NgModule } from '@angular/core';
import { CommonModule as NgCommonModule } from '@angular/common';
import { RouterModule } from '@angular/router';
import { FormsModule } from '@angular/forms';

import { CommonModule } from '../../../../common/common.module';

import { OpspotVideoProgressBar } from './progress-bar/progress-bar.component';
import { OpspotVideoQualitySelector } from './quality-selector/quality-selector.component';
import { OpspotVideoVolumeSlider } from './volume-slider/volume-slider.component';

import { VideoAdsDirective } from './ads.directive';
import { VideoAds, OpspotVideoComponent } from './video.component';
import { OpspotVideoDirectHttpPlayer } from './players/direct-http.component';
import { OpspotVideoTorrentPlayer } from './players/torrent.component';

@NgModule({
  imports: [
    NgCommonModule,
    CommonModule,
    FormsModule,
    RouterModule.forChild([])
  ],
  declarations: [
    VideoAdsDirective,
    VideoAds,
    OpspotVideoComponent,
    OpspotVideoDirectHttpPlayer,
    OpspotVideoTorrentPlayer,
    OpspotVideoProgressBar,
    OpspotVideoQualitySelector,
    OpspotVideoVolumeSlider,
  ],
  exports: [
    VideoAdsDirective,
    VideoAds,
    OpspotVideoComponent,
  ],
})
export class VideoModule {
}
