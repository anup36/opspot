import { Component, EventEmitter } from '@angular/core';

import { Client } from '../../../../services/api';

@Component({
  selector: 'opspot-banner-fat',
  inputs: ['_object: object', '_src: src', '_editMode: editMode', '_done: done'],
  outputs: ['added'],
  template: `
  <div class="opspot-banner" *ngIf="!editing">
    <img [src]="src" class="opspot-banner-img" />
  </div>
  <div *ngIf="editing" class="opspot-banner opspot-banner-editing">
    <img [src]="src" class="opspot-banner-img"/>
    <div class="overlay" [hidden]="file">
      <i class="material-icons">camera</i>
      <span i18n="@@OPSPOT__BANNER__ADD_NEW_BANNER">Click here to add a new banner</span>
    </div>

    <button class="add-button mdl-button mdl-button--raised mdl-button--colored material-icons" (click)="onClick($event)">
      <i class="material-icons">file_upload</i>
    </button>
    <input type="file" id="file" (change)="add($event)" [hidden]="file" />
  </div>
  `
})

export class OpspotFatBanner {

  opspot: Opspot = window.Opspot;
  object;
  editing: boolean = false;
  src: string = '';
  index: number = 0;

  file: any;
  top: number = 0;
  added: EventEmitter<any> = new EventEmitter();

  set _object(value: any) {
    if (!value)
      return;
    this.object = value;
    this.src = '/fs/v1/banners/' + this.object.guid + '/' + this.top + '/' + this.object.last_updated;
  }

  set _src(value: any) {
    this.src = value;
  }

  set _top(value: number) {
    if (!value)
      return;
    this.top = value;
  }

  set _editMode(value: boolean) {
    this.editing = value;
  }

  add(e) {
    if (!this.editing)
      return;

    var element: any = e.target ? e.target : e.srcElement;
    this.file = element ? element.files[0] : null;

    /**
     * Set a live preview
     */
    var reader = new FileReader();
    reader.onloadend = () => {
      this.src = typeof reader.result === 'string' ? reader.result : reader.result.toString();
    };
    reader.readAsDataURL(this.file);

    element.value = '';
  }

  cancel() {
    this.file = null;
  }

  /**
   * An upstream done event, which triggers the export process. Usually called from carousels
   */
  set _done(value: boolean) {
    if (value)
      this.done();
  }

  done() {
    this.added.next({
      index: this.index,
      file: this.file,
      top: this.top
    });
    this.file = null;
    //this.editing = false;
  }

  onClick(e) {
    e.target.parentNode.parentNode.getElementsByTagName('input')[0].click();
  }

}
