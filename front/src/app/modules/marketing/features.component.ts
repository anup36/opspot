import { Component, Input } from '@angular/core';
import { Router } from '@angular/router';

@Component({
  selector: 'm-marketing--features',
  templateUrl: 'features.component.html'
})

export class MarketingFeaturesComponent {

  opspot = window.Opspot;

  @Input() panels = { 
    newsfeed: true
  };

  constructor(
  ) {

  }

}
