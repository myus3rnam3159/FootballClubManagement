import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { AuthGuard } from '../core/guards/auth.guard';
import { ShopComponent } from '../shop/shop.component';
import { ClubsComponent } from './clubs.component';

const routes: Routes = [
  { path: '', component: ClubsComponent },
  {
    path: ':clubid',
    component: ShopComponent,
    data: { breadcrumb: 'Danh sách cầu thủ' },
  },
  {
    path: 'updateclub',
    canActivate: [AuthGuard],
    loadChildren: () =>
      import('./club-item-details/club-item-details.module').then(
        (mod) => mod.ClubItemDetailsModule
      ),
    data: { breadcrumb: { skip: true } },
  },
];

@NgModule({
  declarations: [],
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class ClubsRoutingModule {}
