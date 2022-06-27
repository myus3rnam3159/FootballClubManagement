import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { IntroComponent } from './intro.component';

const routes: Routes = [{ path: '', component: IntroComponent }];

@NgModule({
    declarations: [],
    imports: [
      RouterModule.forChild(routes),
    ],
    exports: [RouterModule],
  })
  export class IntroRoutingModule {}