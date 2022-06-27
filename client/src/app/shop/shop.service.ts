import { throwError } from 'rxjs';
import {
  HttpClient,
  HttpParams,
  HttpErrorResponse,
} from '@angular/common/http';
import { Injectable } from '@angular/core';
import { IPagination } from '../shared/models/pagination';
import { map, catchError } from 'rxjs/operators';
import { ShopParams } from '../shared/models/shopParams';
import { IPlayer, IPlayerResponse } from '../shared/models/player';
import { IClub } from '../shared/models/club';
import { INumber } from '../shared/models/number';
import { INationality } from '../shared/models/nationality';

@Injectable({
  providedIn: 'root',
})
export class ShopService {
  baseUrl = 'http://localhost:80/controller/';
  constructor(private http: HttpClient) {}

  public handleError(error: HttpErrorResponse) {
    console.error(error.message);
    return throwError('A data error occurred, please try again.');
  }

  //Lấy cầu thủ theo id
  getPlayer(id: number){
    return this.http.get<IPlayerResponse>('http://localhost:80/players/'+ id).pipe(
      map((resp) => {
        return resp.data;
      })
    );
  }

  //Xóa một cầu thủ
  deletePlayer(id: number){
    return this.http.delete('http://localhost:80/players/'+ id).pipe(
      map((res: IPlayerResponse) => {
        return res;
      }),
      catchError(this.handleError)
    );
  }

  //Cập nhật một cầu thủ
  updatePlayer(player: IPlayer){
    return this.http.patch(this.baseUrl + 'PlayersController.php', player)
      .pipe(
        map((response: IPlayerResponse) => {
          return response.data;
        }),
        catchError(this.handleError)
      );
  }

  //Thêm cầu thủ mới
  addPlayer(player: IPlayer) {
    return this.http
      .post(this.baseUrl + 'PlayersController.php', player)
      .pipe(
        map(
          (response: IPlayerResponse) => {
            return response.data;
          }
        ),
        catchError(this.handleError)
      );
  }

  //Lấy danh sách quốc tịch
  getNationalities() {
    return this.http.get<INationality>(
      this.baseUrl + 'NationalitiesController.php'
    );
  }

  //Lấy danh sách số áo
  getNumbers() {
    return this.http.get<INumber>(this.baseUrl + 'NumbersController.php');
  }

  //Lấy danh sách câu lạc bộ
  getClubs() {
    return this.http.get<IClub>(this.baseUrl + 'ClubsController.php');
  }

  //Lấy danh sách cầu thủ
  getProducts(shopParams: ShopParams) {
    let params = new HttpParams();

    //thêm tham số trang
    params = params.append('pageIndex', shopParams.pageNumber.toString());

    //Thêm quốc tịch
    if (shopParams.nationality !== 'Tất cả') {
      params = params.append('nationality', shopParams.nationality);
    }

    //Thêm số áo
    if (shopParams.number !== 0) {
      params = params.append('number', shopParams.number.toString());
    }

    //Thêm tham số câu lạc bộ
    if (shopParams.club > 0) {
      params = params.append('club', shopParams.club.toString());
    }

    //Search param
    if (shopParams.search) {
      params = params.append('search', shopParams.search);
    }
    console.log(params);

    return this.http
      .get<IPagination>(this.baseUrl + 'PlayersController.php', {
        observe: 'response',
        params,
      })
      .pipe(
        map((response) => {
          console.log(response.body);
          return response.body;
        })
      );
  }
}
