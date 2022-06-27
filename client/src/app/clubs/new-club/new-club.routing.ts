import { Routes, RouterModule } from '@angular/router';
import { NewClubComponent } from './new-club.component';

const newClubRoutes: Routes = [
    {path: '', component: NewClubComponent}
];
export const newClubRouting = RouterModule.forChild(newClubRoutes);