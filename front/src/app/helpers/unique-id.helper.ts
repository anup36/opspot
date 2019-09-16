export class UniqueId {
  static counter = 1000;

  static generate(prefix: string = 'id') {
    UniqueId.counter++;
    return `opspot-${prefix}--${UniqueId.counter}`;
  }
}
