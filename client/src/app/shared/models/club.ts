export interface IOneClub {
    statusCode: number;
    success:    boolean;
    messages:   any[];
    data:       Club;
}

export interface IClub {
    statusCode: number;
    success:    boolean;
    messages:   any[];
    data:       Club[];
}

export interface Club {
    clubid:    number;
    clubname:  string;
    shortname: string;
    stadiumid: string;
    coachid:   number;
}