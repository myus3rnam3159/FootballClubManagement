import { Component, OnInit } from '@angular/core';
import { FormControl, FormGroup, Validators } from '@angular/forms';
import { ActivatedRoute, Router } from '@angular/router';
import { AccountService } from '../account.service';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.scss'],
})
export class LoginComponent implements OnInit {
  loginForm: FormGroup;
  returnUrl: string;

  constructor(
    private accountService: AccountService,
    private router: Router,
    private activateRoute: ActivatedRoute
  ) {}

  ngOnInit(): void {
    //Tạo login form
    this.createLoginForm();

    this.returnUrl =
      this.activateRoute.snapshot.queryParams.returnUrl || '/home';
    
  }

  createLoginForm() {
    this.loginForm = new FormGroup({

       //Userid chỉ toàn số, 8 đến 15 kí tự
      userid: new FormControl('', [
        Validators.required,
       
        Validators.pattern('^[0-9]{8,15}$'),
      ]),

      //Mật khẩu không cách, không dấu, không kí tự đặc biệt, có số, có viết hoa 8 đến 15 kí tự
      upassword: new FormControl('', [
        Validators.required,
        Validators.pattern('^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9]).{8,15}$'),
      ]),
    });
  }

  onSubmit() {
    //Test lognin value
    //console.log(this.loginForm.value);

    this.accountService.login(this.loginForm.value).subscribe(
      () => {
        console.log('User loged in');
        //Test url state
        //console.log(this.returnUrl);
        
        this.router.navigateByUrl(this.returnUrl);
      },
      (error) => {
        console.log(error);
      }
    );
  }
}
