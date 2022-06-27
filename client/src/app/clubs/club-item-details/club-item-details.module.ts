import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ReactiveFormsModule } from '@angular/forms';
import { SharedModule } from 'src/app/shared/shared.module';
import { ClubItemDetailsComponent } from './club-item-details.component';
import { clubItemDetailsRouting } from './club-item-details.routing';

@NgModule({
    declarations: [
        ClubItemDetailsComponent
    ],
    imports: [
        CommonModule,
        ReactiveFormsModule,
        SharedModule,
        clubItemDetailsRouting
    ]
})
export class ClubItemDetailsModule{}