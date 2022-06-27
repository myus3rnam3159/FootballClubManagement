export interface IClubsPagination {
  statusCode: number;
  success:    boolean;
  messages:   any[];
  data:       Data;
}

export interface Data {
  pageIndex: number;
  pageSize:  number;
  count:     number;
  clubs:   IClub[];
}

export interface IClub {
    clubid:    number;
    clubname:  string;
    shortname: string;
    stadiumid: string;
    coachid:   number;
}