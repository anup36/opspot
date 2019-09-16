import { ChangeDetectionStrategy, Component, OnInit } from '@angular/core';

@Component({
  moduleId: module.id,
  selector: 'm-boost--marketing',
  templateUrl: 'marketing.component.html',
  changeDetection: ChangeDetectionStrategy.OnPush
})

export class BoostMarketingComponent {

  opspot = window.Opspot;
  user = window.Opspot.user;

}
