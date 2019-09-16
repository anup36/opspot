import { Component, EventEmitter } from '@angular/core';

import { Client } from '../../../services/api';

@Component({
  moduleId: module.id,
  selector: 'opspot-onboarding-categories-selector',
  outputs: ['done'],
  templateUrl: 'categories-selector.html',
})

export class OnboardingCategoriesSelector {

  opspot = window.Opspot;

  categories: Array<any> = [];

  inProgress: boolean = false;
  done: EventEmitter<any> = new EventEmitter();

  constructor(public client: Client) {

  }

  ngOnInit() {
    this.initCategories();
  }

  initCategories() {
    delete window.Opspot.categories.other;
    for (let category in window.Opspot.categories) {
      this.categories.push({
        id: category,
        label: window.Opspot.categories[category],
        'selected': false
      });
    }
  }

  saveCategories() {
    this.inProgress = true;
    const filteredCategories: any[] = this.categories.filter(category => category.selected).map(category => category.id);
    this.client.post('api/v1/settings', {
      categories: filteredCategories
    })
      .then((response: any) => {
        this.inProgress = false;
        this.done.next(true);
      })
      .catch(() => {
        this.inProgress = false;
      });
  }

}
