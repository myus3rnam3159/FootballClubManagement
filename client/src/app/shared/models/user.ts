

export interface IUser {
    statusCode: number;
    success:    boolean;
    messages:   string[];
    data:       Data;
}

export interface Data {
    userid:    string;
    uname:     string;
    upassword: string;
    ustatus:   boolean;
}