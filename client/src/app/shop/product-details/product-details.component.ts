import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { ShopService } from '../shop.service';
import { IPlayer } from 'src/app/shared/models/player';
import { FormBuilder, FormControl, FormGroup, Validators } from '@angular/forms';

@Component({
  selector: 'app-product-details',
  templateUrl: './product-details.component.html',
  styleUrls: ['./product-details.component.scss'],
})
export class ProductDetailsComponent implements OnInit {

  constructor(
    private formBuilder: FormBuilder,
    private shopService: ShopService,
    private activatedRoute: ActivatedRoute
  ) {}

  ngOnInit(): void {
    this.createPlayerForm();
    this.loadSelectedPlayer();
  }

  playerForm: FormGroup;
  playerData: IPlayer;

  onDelete(){
    const playerid = parseInt(this.activatedRoute.snapshot.paramMap.get('id'));
    this.playerForm.reset();
    this.shopService.deletePlayer(playerid).subscribe(
      (resStatus) => {
        if(resStatus.statusCode === 200){
          console.log('Player deleted!');
        }
      },
      (error) => {
        console.log(error);
      }
    );
  }

  onSubmit() {
    this.playerData = this.playerForm.value;
    this.playerData.playerid = parseInt(
      this.activatedRoute.snapshot.paramMap.get('id')
    );
    this.shopService.updatePlayer(this.playerData).subscribe(
      (res) => {
        this.playerData = res;
      },
      (error) => {
        console.log(error);
      }
    );
  }

  loadSelectedPlayer() {
    const playerid = parseInt(this.activatedRoute.snapshot.paramMap.get('id'));
    this.shopService.getPlayer(playerid).subscribe(
      (playerInfo) => {
        this.playerForm.patchValue({fullname: playerInfo.fullname});
        this.playerForm.patchValue({position: playerInfo.position});
        this.playerForm.patchValue({nationality: playerInfo.nationality});
        this.playerForm.patchValue({number: playerInfo.number});
      },
      (error) => {
        console.log(error);
      }
    );
    
  }

  createPlayerForm() {
    this.playerForm = this.formBuilder.group({
      fullname: new FormControl('', Validators.pattern('^[a-zA-Z\\s]*$')),
      position: new FormControl('', Validators.pattern('^[A-Z]{0,2}[A-Z]$')),
      nationality: new FormControl('', Validators.pattern('^[a-zA-Z\\s]*$')),
      number: new FormControl('', Validators.pattern('^[0-9]+$'))
    });
  }
}
