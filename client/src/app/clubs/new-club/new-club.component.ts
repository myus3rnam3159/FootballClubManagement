import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { Club } from 'src/app/shared/models/club';
import { ClubsService } from '../clubs.service';

@Component({
  selector: 'app-new-club',
  templateUrl: './new-club.component.html',
  styleUrls: ['./new-club.component.scss'],
})
export class NewClubComponent implements OnInit {
  constructor(
    private formBuilder: FormBuilder,
    private clubService: ClubsService
  ) {}

  clubData: Club;
  clubForm: FormGroup;

  ngOnInit(): void {
    this.createClubForm();
  }

  onSubmit(){
    this.clubData = this.clubForm.value;
    this.clubService.addClub(this.clubData).subscribe(
        (res) => {
            this.clubData = res;
            //Test club data
            console.log(this.clubData);
        },
        (error) => {
          console.log(error);
        }
    );
  }

  createClubForm() {
    this.clubForm = this.formBuilder.group({
      clubname: this.formBuilder.control(
        '',
        Validators.compose([
          Validators.required,
          Validators.pattern('^[a-zA-Z\\s]*$'),
        ])
      ),
      shortname: this.formBuilder.control(
        '',
        Validators.compose([
          Validators.required,
          Validators.pattern('^[A-Z]{0,2}[A-Z]$'),
        ])
      ),
    });
  }
}
