import { AccountService } from 'src/app/account/account.service';
import { Component, OnInit, Inject } from '@angular/core';
import { FormGroup, Validators, FormBuilder } from '@angular/forms';
import { Club } from 'src/app/shared/models/club';
import { IPlayer } from 'src/app/shared/models/player';
import { ShopService } from '../shop.service';

@Component({
  selector: 'app-new-player',
  templateUrl: './new-player.component.html',
  styleUrls: ['./new-player.component.scss'],
})
export class NewPlayerComponent implements OnInit {
  constructor(
    private formBuilder: FormBuilder,
    private shopService: ShopService,
    
  ) {}

  clubList: Club[];
  clubId: number;
  clubName: string;
  playerData: IPlayer ;
  playerForm: FormGroup;
  

  ngOnInit(): void {
    this.getClubList();
    this.createPlayerForm();
  }

  onClubSelected(clubId: number){
    this.clubId = clubId;
    this.clubName = this.clubList.find((c) => c.clubid == clubId).clubname;
  }

  getClubList() {
    this.shopService.getClubs().subscribe(
      (res) => {
        this.clubList = res.data;
        this.clubList.unshift({
            clubid: 0,
            clubname: 'Tất cả',
            shortname: null,
            stadiumid: null,
            coachid: null,
          });
      },
      (error) => {
        console.log(error);
      }
    );
  }

  createPlayerForm() {
    this.playerForm = this.formBuilder.group({
      fullname: this.formBuilder.control(
        '',
        Validators.compose([
          Validators.required,
          Validators.pattern('^[a-zA-Z\\s]*$'),
        ])
      ),
      position: this.formBuilder.control(
        '',
        Validators.compose([
          Validators.required,
          Validators.pattern('^[A-Z]{0,2}[A-Z]$'),
        ])
      ),
      nationality: this.formBuilder.control(
        '',
        Validators.compose([
          Validators.required,
          Validators.pattern('^[a-zA-Z\\s]*$'),
        ])
      ),
      number: this.formBuilder.control(
        '',
        Validators.compose([Validators.required, Validators.pattern('^[0-9]+$')])
      ),
    });
  }

  onSubmit() {
    this.playerData = this.playerForm.value;
    this.playerData.clubid = this.clubId;

    this.shopService.addPlayer(this.playerData).subscribe(
      (res) => {
        this.playerData = res;
        //Test player data
        console.log(this.playerData);
      },
      (error) => {
        console.log(error);
      }
    );
  }
}
