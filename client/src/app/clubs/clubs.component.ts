import { Component, OnDestroy, OnInit } from '@angular/core';
import { Subscription } from 'rxjs';
import { IClub } from '../shared/models/clubsPagination';
import { ShopParams } from '../shared/models/shopParams';
import { ClubsService } from './clubs.service';

@Component({
  selector: 'app-clubs',
  templateUrl: './clubs.component.html',
  styleUrls: ['./clubs.component.scss'],
})
export class ClubsComponent implements OnInit, OnDestroy {
  clubListUpdated: boolean;
  clubListSub: Subscription;

  clubList: IClub[];
  shopParams = new ShopParams();
  totalCount: number;

  constructor(private clubsService: ClubsService) {}

  ngOnInit(): void {
    this.loadClubListStatus();
    if (this.clubListUpdated === null || this.clubListUpdated === false) {
      this.getClubs();
    }
  }

  ngOnDestroy(): void {
    if (this.clubListUpdated === true) {
      this.clubListSub.unsubscribe();
    }
  }

  loadClubListStatus() {
    //Flag không cần tải lại clubList
    this.clubsService.clubListUpdateStatusSource.next(null);

    if (this.clubsService.currentClubListUpdateStatus$ === null){
      this.clubListUpdated = false;
    } else {
      this.clubsService.currentClubListUpdateStatus$.subscribe((val) => {
        this.clubListUpdated = val;
      });
    }
  }

  getClubs() {
    this.clubListSub = this.clubsService.getClubs(this.shopParams).subscribe(
      (response) => {
        this.clubList = response.data.clubs;
        this.shopParams.pageNumber = response.data.pageIndex;
        this.totalCount = response.data.count;
      },
      (error) => {
        console.log(error);
      }
    );
  }
  //Thay đổi số trang
  onPageChanged(event: any) {
    if (this.shopParams.pageNumber != event) {
      this.shopParams.pageNumber = event;
      this.getClubs();
    }
  }
}
