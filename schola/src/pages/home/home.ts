import { Component, OnInit } from '@angular/core';
import { NavController } from 'ionic-angular';
import { ParseServiceProvider } from '../../providers/parse_service_provider';
import { LoginPage } from '../login/login';

@Component({
  selector: 'page-home',
  templateUrl: 'home.html',
})
export class HomePage {

  constructor(public navCtrl: NavController, public Parse: ParseServiceProvider) {

  }

  

  ngOnInit() {
  }
  
  nextPage(){
    this.navCtrl.push(LoginPage);
  }

}
