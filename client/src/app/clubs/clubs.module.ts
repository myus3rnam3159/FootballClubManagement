import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ClubsComponent } from './clubs.component';
import { ClubItemComponent } from './club-item/club-item.component';
import { SharedModule } from '../shared/shared.module';
import { ClubsRoutingModule } from './clubs-routing.module';

@NgModule({
  declarations: [ClubsComponent, ClubItemComponent],
  imports: [CommonModule, SharedModule, ClubsRoutingModule],
})
export class ClubsModule {}
