import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import {
  FormBuilder,
  FormControl,
  FormGroup,
  Validators,
} from '@angular/forms';
import { ClubsService } from '../clubs.service';
import { Club } from 'src/app/shared/models/club';

@Component({
  selector: 'app-club-details',
  templateUrl: './club-item-details.component.html',
  styleUrls: ['./club-item-details.component.scss'],
})
export class ClubItemDetailsComponent implements OnInit {
  constructor(
    private formBuilder: FormBuilder,
    private clubService: ClubsService,
    private activatedRoute: ActivatedRoute
  ) {}

  ngOnInit(): void {
    this.createClubForm();
    this.loadSelectedClub();
  }

  club: Club;
  clubForm: FormGroup;

  onDelete() {
    const playerid = parseInt(this.activatedRoute.snapshot.paramMap.get('id'));
    this.clubForm.reset();
    this.clubService.deleteClub(playerid).subscribe(
      (resStatus) => {
        if (resStatus.statusCode === 200) {
          console.log('Club deleted!');
        }
      },
      (error) => {
        console.log(error);
      }
    );
  }

  onSubmit() {
    this.club = this.clubForm.value;
    this.club.clubid = parseInt(
      this.activatedRoute.snapshot.paramMap.get('id')
    );
    this.clubService.updateClub(this.club).subscribe(
      (res) => {
        this.club = res;
      },
      (error) => {
        console.log(error);
      }
    );
  }

  createClubForm() {
    this.clubForm = this.formBuilder.group({
      clubname: new FormControl('', Validators.pattern('^[a-zA-Z\\s]*$')),
      shortname: new FormControl('', Validators.pattern('^[A-Z]{0,2}[A-Z]$')),
    });
  }

  loadSelectedClub() {
    const clubid = parseInt(this.activatedRoute.snapshot.paramMap.get('id'));
    this.clubService.getClub(clubid).subscribe(
      (clubInfo) => {
        //Test
        console.log(clubInfo);
        this.clubForm.patchValue({ clubname: clubInfo.clubname });
        this.clubForm.patchValue({ shortname: clubInfo.shortname });
      },
      (error) => {
        console.log(error);
      }
    );
  }
}
