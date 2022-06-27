import { NgModule } from '@angular/core';
import { LoginComponent } from './login/login.component';
import { RouterModule, Routes } from '@angular/router';

const routes: Routes = [
  {
    path: 'login',
    component: LoginComponent,
    data: { breadcrumb: 'Đăng nhập' },
  },
];

@NgModule({
  declarations: [],
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class AccountRoutingModule {}
