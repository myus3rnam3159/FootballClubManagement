import { Component, Input, OnInit } from '@angular/core';

import { IPlayer } from 'src/app/shared/models/player';

@Component({
  selector: 'app-product-item',
  templateUrl: './product-item.component.html',
  styleUrls: ['./product-item.component.scss']
})
export class ProductItemComponent implements OnInit {
  @Input() product: IPlayer;

  constructor() { }

  ngOnInit(): void {
  }

}
