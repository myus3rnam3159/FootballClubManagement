import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ReactiveFormsModule } from '@angular/forms';
import { SharedModule } from 'src/app/shared/shared.module';
import { NewClubComponent } from './new-club.component';
import { newClubRouting } from './new-club.routing';

@NgModule({
    declarations: [
        NewClubComponent
    ],
    imports: [
        CommonModule,
        ReactiveFormsModule,
        SharedModule,
        newClubRouting
    ]
})
export class NewClubModule{}