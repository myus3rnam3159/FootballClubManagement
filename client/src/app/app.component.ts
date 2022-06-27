import { Component, OnInit } from '@angular/core';
import { AccountService } from './account/account.service';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.scss'],
})
//Khi chạy trang chủ - OnInit
export class AppComponent implements OnInit {
  //Tên của app
  title = 'Football Club Management System';

  constructor(private accountService: AccountService) {}

  ngOnInit(): void {
    this.loadCurrentUser();
  }

  loadCurrentUser() {
    const token = localStorage.getItem('token');
    //Test
    console.log(token);
    
    this.accountService.loadCurrentUser(token).subscribe(
      () => {
        console.log('loaded user');
      },
      (error) => {
        console.log(error);
      }
    );
  }
}
