import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { AuthGuard } from './core/guards/auth.guard';
import { NotFoundComponent } from './core/not-found/not-found.component';
import { HomeComponent } from './home/home.component';

const routes: Routes = [
  { path: '', component: HomeComponent, data: { breadcrumb: 'Trang chủ' } },

  {
    path: 'not-found',
    component: NotFoundComponent,
    data: { breadcrumb: 'Not Found' },
  },
  //Thêm cầu thủ - yêu cầu đăng nhập
  {
    path: 'addplayer',
    canActivate: [AuthGuard],
    loadChildren: () =>
      import('./shop/new-player/new-player.module').then(
        (mod) => mod.NewPlayerModule
      ),
    data: { breadcrumb: 'Thêm cầu thủ' },
  },
  //Thêm câu lạc bộ - yêu cầu đăng nhập
  {
    path: 'addclub',
    canActivate: [AuthGuard],
    loadChildren: () =>
    import('./clubs/new-club/new-club.module').then(
      (mod) => mod.NewClubModule
    ),
  data: { breadcrumb: 'Thêm câu lạc bộ' },
  },
  //Giới thiệu
  {
    path: 'intro',
    loadChildren: () =>
      import('./intro/intro.module').then((mod) => mod.IntroModule),
    data: { breadcrumb: { skip: true } },
  },
  //Xem danh sách câu lạc bộ
  {
    path: 'clubs',
    loadChildren: () =>
      import('./clubs/clubs.module').then((mod) => mod.ClubsModule),
    data: { breadcrumb: 'Danh sách câu lạc bộ' },
  },
  //Xem danh sách cầu thủ
  {
    path: 'shop',
    loadChildren: () =>
      import('./shop/shop.module').then((mod) => mod.ShopModule),
    data: { breadcrumb: 'Danh sách cầu thủ' },
  },

  {
    path: 'account',
    loadChildren: () =>
      import('./account/account.module').then((mod) => mod.AccountModule),
    data: { breadcrumb: { skip: true } },
  },
  { path: '**', redirectTo: '', pathMatch: 'full' },
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule],
})
export class AppRoutingModule {}
