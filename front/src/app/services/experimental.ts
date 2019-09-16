export class Experimental {

  feature(feature: string): boolean {
    return window.Opspot.user &&
      window.Opspot.user.feature_flags &&
      window.Opspot.user.feature_flags.length &&
      window.Opspot.user.feature_flags.indexOf(feature) > -1;
  }

}
