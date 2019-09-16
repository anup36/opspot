import { ModuleWithProviders } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { APP_BASE_HREF } from '@angular/common';

import { MediaViewComponent } from '../modules/media/view/view.component';

export const OpspotEmbedRoutes: Routes = [
  { path: 'api/v1/embed/:guid', component: MediaViewComponent }
];

export const OpspotEmbedRoutingProviders: any[] = [{ provide: APP_BASE_HREF, useValue: '/' }];
export const OPSPOT_EMBED_ROUTING_DECLARATIONS: any[] = [
  MediaViewComponent,
];
