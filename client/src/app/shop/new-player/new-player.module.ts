import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ReactiveFormsModule } from '@angular/forms';
import { SharedModule } from 'src/app/shared/shared.module';
import { NewPlayerComponent } from './new-player.component';
import { newPlayerRouting, /*NewPlayerRoutingModule*/ } from './new-player.routing';

@NgModule({
    declarations: [
        NewPlayerComponent
    ],
    imports: [
        CommonModule,
        ReactiveFormsModule,
        SharedModule,
        newPlayerRouting,
    ]
})
export class NewPlayerModule{}