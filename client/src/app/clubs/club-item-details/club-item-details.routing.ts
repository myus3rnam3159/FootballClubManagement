import { Routes, RouterModule } from '@angular/router';
import { ClubItemDetailsComponent } from './club-item-details.component';

const clubItemDetailsRoutes: Routes = [
  {
    path: ':id',
    component: ClubItemDetailsComponent,
    data: { breadcrumb: { skip: true } },
  },
];
export const clubItemDetailsRouting = RouterModule.forChild(
  clubItemDetailsRoutes
);
