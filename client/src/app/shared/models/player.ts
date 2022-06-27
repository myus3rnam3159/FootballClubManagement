export interface IPlayerResponse {
  statusCode: number;
  success:    boolean;
  messages:   any[];
  data:       IPlayer;
}

export interface IPlayer {
    playerid: number;
    fullname: string;
    clubid: number;
    dob: string;
    position: string;
    nationality: string;
    number:string;
}