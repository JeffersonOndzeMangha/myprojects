import { Component } from '@angular/core';
import { IonicPage, NavController, NavParams } from 'ionic-angular';
import { ParseServiceProvider } from '../../providers/parse_service_provider';
import { MainPage } from '../main/main';
import { SignUpPage } from '../signup/signup';


/**
 * Generated class for the LoginPage page.
 *
 * See https://ionicframework.com/docs/components/#navigation for more info on
 * Ionic pages and navigation.
 */

@IonicPage()
@Component({
  selector: 'page-login',
  templateUrl: 'login.html',
})
export class LoginPage {

  user:any = {};

  constructor(public navCtrl: NavController, public navParams: NavParams, private Parse: ParseServiceProvider) {
    this.user.name = "jefferson";
  }



  ionViewDidLoad() {
    console.log('On Login Page '+this.user.name);
    this.Parse.parseInitialize();
  }

  loginForm(){
    this.navCtrl.push(MainPage);
  }

  signupButton(){
    this.navCtrl.push(SignUpPage);
  }

}
