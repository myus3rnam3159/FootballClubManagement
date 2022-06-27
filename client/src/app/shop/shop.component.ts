import { Component, ElementRef, OnInit, ViewChild } from '@angular/core';
import { IClub } from '../shared/models/club';
import { IPlayer } from '../shared/models/player';
import { ShopParams } from '../shared/models/shopParams';
import { ShopService } from './shop.service';
import { INumber } from '../shared/models/number';
import { INationality } from '../shared/models/nationality';
import { ActivatedRoute, Router } from '@angular/router';

@Component({
  selector: 'app-shop',
  templateUrl: './shop.component.html',
  styleUrls: ['./shop.component.scss'],
})
export class ShopComponent implements OnInit {
  //Tìm kiếm
  @ViewChild('search', { static: false }) searchTerm: ElementRef;

  //Mã câu lạc bộ khi chọn xem danh sách cầu thủ từ một câu lạc bộ ở clubs component
  clubNumber: number;

  //Danh sách cầu thủ
  players: IPlayer[];

  //Danh sách câu lạc bộ
  clubs: IClub;

  //Danh sách số áo
  numbers: INumber;

  //Danh sách quốc tịch
  nationalities: INationality;

  //Paging, Sorting
  shopParams = new ShopParams();

  totalCount: number;

  constructor(
    private shopService: ShopService,
    private activatedRoute: ActivatedRoute,
    private router: Router
  ) {}

  ngOnInit(): void {
    this.getNumbers();
    this.getNationalities();
    this.getClubs();

    //Lấy giá trị clubid đã được chọn
    this.clubNumber = +this.activatedRoute.snapshot.paramMap.get('clubid');
    if (this.clubNumber) {
      this.onClubSelected(this.clubNumber);
    } else {
      
      this.getProducts();
    }
  }

  //Lấy danh sách quốc tịch
  getNationalities() {
    this.shopService.getNationalities().subscribe(
      (response) => {
        this.nationalities = response;
        //Thêm mặc định tất cả vào mảng
        this.nationalities.data.unshift('Tất cả');
      },
      (error) => {
        console.log(error);
      }
    );
  }

  //Lấy danh sách số áo
  getNumbers() {
    this.shopService.getNumbers().subscribe(
      (response) => {
        this.numbers = response;
        //Thêm mặc định tất cả vào mảng
        this.numbers.data.unshift('Tất cả');
      },
      (error) => {
        console.log(error);
      }
    );
  }

  //Lấy danh sách câu lạc bộ
  getClubs() {
    this.shopService.getClubs().subscribe(
      (response) => {
        this.clubs = response;
        //Thêm giá trị mặc định vào mảng clubs
        this.clubs.data.unshift({
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

  //Lấy danh sách cầu thủ
  getProducts() {
    this.shopService.getProducts(this.shopParams).subscribe(
      (response) => {
        this.players = response.data.players;
        //Test players
        console.log(this.players);
        
        this.shopParams.pageNumber = response.data.pageIndex;
        this.totalCount = response.data.count;
      },
      (error) => {
        console.log(error);
      }
    );
  }

  //Thay đối quốc tịch
  onNationalitySelected(nationality: string) {
    this.shopParams.nationality = nationality;
    this.shopParams.pageNumber = 1;
    this.getProducts();
  }

  //Thay đổi số áo
  onNumberSelected(number: any) {
    if (number === 'Tất cả') {
      this.shopParams.number = 0;
    } else {
      this.shopParams.number = number;
    }
    this.shopParams.pageNumber = 1;
    this.getProducts();
  }

  //Thay đổi clubid
  onClubSelected(clubId: number) {
    this.shopParams.club = clubId;
    this.shopParams.pageNumber = 1;
    this.getProducts();
  }
  //Thay đổi số trang
  onPageChanged(event: any) {
    if (this.shopParams.pageNumber != event) {
      this.shopParams.pageNumber = event;
      this.getProducts();
    }
  }
  //Tìm kiếm
  onSearch() {
    this.shopParams.search = this.searchTerm.nativeElement.value;
    this.shopParams.pageNumber = 1;
    this.getProducts();
  }
  //Đặt lại
  onReset() {
    /*this.clubNumber = null;
    this.searchTerm.nativeElement.value = '';
    this.shopParams = new ShopParams();
    this.getProducts();*/
    
    window.location.reload();
    
  }
}
