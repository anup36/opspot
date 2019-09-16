import { OpspotUser, OpspotGroup } from './entities';

/*
* Opspot response object
*/
export interface OpspotResponse { }

export interface OpspotChannelResponse extends OpspotResponse {
  status: string;
  message: string;
  channel: OpspotUser;
}

export interface OpspotBlogResponse extends OpspotResponse {
  blog: any;
}

export interface OpspotBlogListResponse extends OpspotResponse {
  blogs: Array<any>;
  entities: Array<any>
  'load-next': string;
  pageToken?: boolean;
}


export interface OpspotUserConversationResponse extends OpspotResponse {
  publickeys: any;
  messages: Array<any>;
  'load-previous': string;
  'load-next': string;
}

export interface OpspotGatheringsSearchResponse extends OpspotResponse {
  user: Array<any>;
}

export interface OpspotKeysResponse extends OpspotResponse {
  key: any;
}

export interface OpspotConversationResponse extends OpspotResponse {
  conversations: Array<any>;
  'load-next': string;
}

export interface OpspotGroupResponse extends OpspotResponse {
  group: OpspotGroup;
}

export interface OpspotGroupListResponse extends OpspotResponse {
  groups: Array<any>;
  'load-next': string;
  pageToken?: boolean;
}

export interface OpspotWalletResponse extends OpspotResponse {
  boost_rate: number;
  btc: string;
  cap: number;
  count: number;
  ex: any;
  min: number;
  satoshi: number;
  status: string;
  usd: number;
}

export interface OpspotBoostRateResponse extends OpspotResponse {
  rate: number;
  balance: number;
  min: number;
  cap: number;
}

export interface OpspotBoostResponse extends OpspotResponse {
  status: string;
}

export interface OpspotUserSearchResponse extends OpspotResponse {
  entities: Array<any>;
}
