import { Routes, RouterModule } from '@angular/router';
import { NewPlayerComponent } from './new-player.component';

const newPlayerRoutes: Routes = [
    {path: '', component: NewPlayerComponent}
];
export const newPlayerRouting = RouterModule.forChild(newPlayerRoutes);
