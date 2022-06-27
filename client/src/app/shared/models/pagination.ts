import { IPlayer } from './player';

export interface IPagination {
  statusCode: number;
  success:    boolean;
  messages:   any[];
  data:       Data;
}

export interface Data {
  pageIndex: number;
  pageSize:  number;
  count:     number;
  players:   IPlayer[];
}