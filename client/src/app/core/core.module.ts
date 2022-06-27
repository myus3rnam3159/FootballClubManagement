import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { NavBarComponent } from './nav-bar/nav-bar.component';
import { RouterModule } from '@angular/router';
import { NotFoundComponent } from './not-found/not-found.component';

import {ToastrModule } from 'ngx-toastr';
import {BreadcrumbModule} from 'xng-breadcrumb';
import { SectionHeaderComponent } from './section-header/section-header.component';
import { SharedModule } from '../shared/shared.module';

@NgModule({
  declarations: [
    NavBarComponent,

    NotFoundComponent,
    SectionHeaderComponent,
  ],
  imports: [
    CommonModule,
    RouterModule,
    BreadcrumbModule,
    SharedModule,
    ToastrModule.forRoot({
      positionClass: 'toast-bottom-right',
      preventDuplicates: true,
    }),
  ],
  exports: [NavBarComponent, SectionHeaderComponent],
})
export class CoreModule {}
