import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { PaginationModule } from 'ngx-bootstrap/pagination';
import { PagingHeaderComponent } from './components/paging-header/paging-header.component';
import { PagerComponent } from './components/pager/pager.component';
import { CarouselModule } from 'ngx-bootstrap/carousel';

import { ReactiveFormsModule } from '@angular/forms';
import { BsDropdownModule } from 'ngx-bootstrap/dropdown';



import { RouterModule } from '@angular/router';

@NgModule({
  declarations: [
    PagingHeaderComponent,
    PagerComponent,
  ],
  imports: [
    CommonModule,
    PaginationModule.forRoot(),
    CarouselModule.forRoot(),
    BsDropdownModule.forRoot(),
    ReactiveFormsModule,
    RouterModule,
  ],
  exports: [
    PaginationModule,
    PagingHeaderComponent,
    PagerComponent,
    CarouselModule,

    ReactiveFormsModule,
    BsDropdownModule,

  ],
})
export class SharedModule {}
