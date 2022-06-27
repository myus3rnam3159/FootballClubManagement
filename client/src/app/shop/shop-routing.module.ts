import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { ShopComponent } from './shop.component';
import { ProductDetailsComponent } from './product-details/product-details.component';
import { AuthGuard } from '../core/guards/auth.guard';

const routes: Routes = [
  { path: '', component: ShopComponent },
  {
    path: ':id',
    canActivate: [AuthGuard],
    component: ProductDetailsComponent,
    data: { breadcrumb: { skip: true } },
  },
];

@NgModule({
  declarations: [],
  imports: [
    RouterModule.forChild(routes),
  ],
  exports: [RouterModule],
})
export class ShopRoutingModule {}
