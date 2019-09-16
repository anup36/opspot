// Type definitions for Opspot
interface Opspot {
  OpspotContext: string;
  OpspotEmbed: any;
  LoggedIn: boolean;
  Admin?: boolean;
  user: any;
  wallet: any;
  navigation: OpspotNavigation | any;
  cdn_url: string;
  cdn_assets_url: string;
  site_url: string;
  cinemr_url: string;
  notifications_count: number;
  socket_server: string;
  thirdpartynetworks: any;
  categories: any;
  languages: any;
  language: any;
  stripe_key?: any;
  recaptchaKey?: string;
  max_video_length?: number;
  features?: any;
  blockchain?: any;
  sale?: boolean | string;
  last_tos_update: number;
  tags: string[]
}

interface OpspotNavigation {
  topbar: any;
  sidebar: any;
}

interface Window {
  Opspot: Opspot;
  componentHandler: any;
  ga: any;
  adsbygoogle?: any;
  onErrorCallback?: any;
  onSuccessCallback?: any;
  BraintreeLoaded?: any;
  io?: any;
  google?: any;
  twoOhSix?: any;
  web3?: any;
  sale?: boolean | string;
}
declare var window: Window;
