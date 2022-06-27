import { Component, Input, OnInit } from '@angular/core';
import {IClub} from '../../shared/models/clubsPagination';

@Component({
  selector: 'app-club-item',
  templateUrl: './club-item.component.html',
  styleUrls: ['./club-item.component.scss']
})
export class ClubItemComponent implements OnInit {
  @Input() club: IClub;

  constructor() { }

  ngOnInit(): void {
  }

}
