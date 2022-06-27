import { HttpClient, HttpHeaders} from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Router } from '@angular/router';
import { of, ReplaySubject } from 'rxjs';
import { map } from 'rxjs/operators';
import { environment } from 'src/environments/environment';
import { IUser, Data } from '../shared/models/user';

@Injectable({
  providedIn: 'root',
})
export class AccountService {
  
  baseUrl = environment.apiUrl;
  private currentUserSource = new ReplaySubject<Data>(1);
  currentUser$ = this.currentUserSource.asObservable();

  constructor(private http: HttpClient, private router: Router) {}

  loadCurrentUser(token: string){
    if(!token){
      this.currentUserSource.next(null);
      return of(null);
    }

    let headers = new HttpHeaders();
    headers = headers.set('Authorization', token);

    return this.http.get(this.baseUrl + 'UsersController.php', {headers}).pipe(
      map((user: IUser) => {
        if(user){
          localStorage.setItem('token', user.data.upassword);
          //Test
          console.log(user.data);
          this.currentUserSource.next(user.data);
        }
      })
    );
  }

  login(values: any) {
    //Test
    console.log(values);

    return this.http.post(this.baseUrl + 'UsersController.php', values).pipe(
      map((user: IUser) => {
        localStorage.setItem('token', user.data.upassword);
        this.currentUserSource.next(user.data);
      })
    );
  }

  logout() {
    localStorage.removeItem('token');
    this.currentUserSource.next(null);
    this.router.navigateByUrl('/');
  }

}
