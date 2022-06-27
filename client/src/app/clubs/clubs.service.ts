import { HttpClient, HttpParams } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { catchError, map } from 'rxjs/operators';
import { ShopParams } from '../shared/models/shopParams';
import { IClubsPagination } from '../shared/models/clubsPagination';
import { Club, IClub, IOneClub } from '../shared/models/club';
import { ShopService } from '../shop/shop.service';
import { ReplaySubject } from 'rxjs';

@Injectable({
  providedIn: 'root',
})
export class ClubsService {
  clubListUpdateStatusSource = new ReplaySubject<boolean>();
  currentClubListUpdateStatus$ = this.clubListUpdateStatusSource.asObservable();

  baseUrl = 'http://localhost:80/controller/';

  constructor(private http: HttpClient, private shopService: ShopService) {}

  deleteClub(id: number) {
    //Phát tín hiệu phải cập nhật lại list
    this.clubListUpdateStatusSource.next(true);

    return this.http.delete('http://localhost:80/clubs/' + id).pipe(
      map((resp: IOneClub) => {
        return resp;
      })
    );
  }

  getClub(id: number) {
    return this.http.get<IOneClub>('http://localhost:80/clubs/' + id).pipe(
      map((resp) => {
        return resp.data;
      })
    );
  }

  updateClub(theClub: Club) {
    //Phát tín hiệu phải cập nhật lại list
    this.clubListUpdateStatusSource.next(true);

    return this.http.patch(this.baseUrl + 'ClubsController.php', theClub).pipe(
      map((response: Club) => {
        return response;
      }),
      catchError(this.shopService.handleError)
    );
  }

  addClub(newClub: Club) {
    //Phát tín hiệu phải cập nhật lại list
    this.clubListUpdateStatusSource.next(true);

    return this.http.post(this.baseUrl + 'ClubsController.php', newClub).pipe(
      map((response: IOneClub) => {
        return response.data;
      }),
      catchError(this.shopService.handleError)
    );
  }

  getClubs(shopParams: ShopParams) {
    let params = new HttpParams();
    //thêm tham số trang
    params = params.append('pageIndex', shopParams.pageNumber.toString());
    //Test
    console.log(params);

    return this.http
      .get<IClubsPagination>(this.baseUrl + 'ClubsController.php', {
        observe: 'response',
        params,
      })
      .pipe(
        map((response) => {
          //Test
          console.log(response.body);
          return response.body;
        })
      );
  }
}
