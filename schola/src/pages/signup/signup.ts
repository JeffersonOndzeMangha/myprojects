import { Component } from '@angular/core';

import { IonicPage, NavController, NavParams, AlertController } from 'ionic-angular';
import { ParseServiceProvider } from '../../providers/parse_service_provider';

import { MainPage } from '../main/main';
import { MoreInfoPage } from '../more-info/more-info';

/**
 * Generated class for the SignUpPage page.
 *
 * See https://ionicframework.com/docs/components/#navigation for more info on
 * Ionic pages and navigation.
 */

@IonicPage()
@Component({
  selector: 'page-signup',
  templateUrl: 'signup.html',
})
export class SignUpPage {

  protected user:any = {};

  constructor(public navCtrl: NavController, public navParams: NavParams, private parseService: ParseServiceProvider, public alert: AlertController) {
  }

  ionViewCanEnter(){
    return this.isLoggedIn();
  }

  ionViewDidLoad() {
    console.log('ionViewDidLoad SignUpPage');
  }
  isLoggedIn(){
    return this.parseService.isLoggedIn();
  }

  signupForm(){
    if(this.parseService.newUserSignUpWithScholaApp(this.user.email, this.user.password, this.user.name, this)){
      this.navCtrl.push(MoreInfoPage);
    };
  }


}
