import { Title } from '@angular/platform-browser';

export class OpspotTitle {

  private counter: number;
  private sep = ' | ';
  private default_title = 'Opspot';
  private text: string = '';

  static _(title: Title) {
    return new OpspotTitle(title);
  }

  constructor(public title: Title) { }

  setTitle(value: string) {
    let title;

    if (value) {
      title = [value, this.default_title].join(this.sep);
    } else {
      title = this.default_title;
    }
    this.text = title;
    this.applyTitle();
  }


  setCounter(value: number) {
    this.counter = value;
    this.applyTitle();

  }

  applyTitle() {
    if (this.counter) {
      this.title.setTitle(`(*) ${this.text}`);
    } else {
      this.title.setTitle(this.text);
    }

  }

}
