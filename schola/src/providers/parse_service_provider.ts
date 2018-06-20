import { Injectable } from '@angular/core';
import { AlertController } from 'ionic-angular';
import { Parse } from 'parse';
import 'rxjs/add/operator/map';

/*
  Generated class for the ParseProvider provider.
  See https://angular.io/docs/ts/latest/guide/dependency-injection.html
  for more info on providers and Angular 2 DI.
*/
@Injectable()
export class ParseServiceProvider {
  private parseAppId: string = "***********";
  private parseServerUrl: string = "*************";

  retToken: any = {};

  protected User: Object;
  public selfparse: any;

  constructor(private alert: AlertController) {
    this.parseInitialize();
    this.User = Parse.User.current();
    this.selfparse = Parse;
  }

  // initialize parse for use in our app... uses the appID and URL
  parseInitialize(){
    Parse.initialize(this.parseAppId);
    Parse.serverURL = this.parseServerUrl;
  }
  
  //Check if a user is logged in
  isLoggedIn(){
    if(this.User != null ){
      this.alert.create({
        title: 'You are already logged in',
        buttons: ['Ok']
      }).present();
      return true;
    }else {
      console.log('NO!');
      return true;
    }
  }

  //New user sign up, using schola forms and app.
  newUserSignUpWithScholaApp($username, $password, $name, $self){
    var user = new Parse.User();
    user.set('username', $username);
    user.set('password', $password);
    user.set('email', $username);
    user.signUp(null, {
      success: function(user){
      },
      error: function(user, error){
       $self.alert.create({
          title: error.message,
          buttons: ['Got it']
        }).present();
      }
    });
    if(Parse.User.current()){
      return true;
    }else {
      return false;
    }
  }

  newUserSignUpWithFacebook(){
    //do something
  }
  returningUserLogInWithScholaApp($username, $password){
    //do something
  }
  returningUserLoginWithFacebook(){
    //do something
  }

}
