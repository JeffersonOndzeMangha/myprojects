import { Component } from '@angular/core';
import { IonicPage, NavController, NavParams, AlertController } from 'ionic-angular';
import { ParseServiceProvider } from '../../providers/parse_service_provider';
import { HTTP } from '@ionic-native/http';
 
import { MainPage } from '../main/main';


/**
 * Generated class for the MoreInfoPage page.
 *
 * See https://ionicframework.com/docs/components/#navigation for more info on
 * Ionic pages and navigation.
 */

@IonicPage()
@Component({
  selector: 'page-more-info',
  templateUrl: 'more-info.html',
})
export class MoreInfoPage {

  protected Parse:any;
  protected user:any = {};
  protected results:any = [];
  protected show:boolean = false;

  constructor(public navCtrl: NavController, public navParams: NavParams, public parseService: ParseServiceProvider, public alert: AlertController, private http: HTTP) {
    this.Parse = this.parseService.selfparse;
  }

  ionViewDidLoad() {
    console.log('ionViewDidLoad MoreInfoPage');
  }

  collegeSearch(text){// searches Google for colleges/universities matching user input
    this.show = true;
    this.http.get('https://maps.googleapis.com/maps/api/place/textsearch/json?key=******************************&type=university&query='+text, {}, {})
      .then(res => {
        if (JSON.parse(res.data)) {
         this.results = JSON.parse(res.data);
         this.results = this.results.results;
        }else {
          console.log('failed');
        }
      })
      .catch(error => {
        console.log(error.error);
      })
  }

  setCollege(college){// sets college once user selects it from results list
    this.user.schoolName = college.name;
    this.user.school = college;
    this.show = false;
  }

  setCollegeInfo($self){// sets the college information from sellected college's infor by google
    console.log('saving college...');
    let school = new $self.Parse.Object.extend('school');
    let query = new $self.Parse.Query(school);
    query.equalTo('place_id', $self.user.school.place_id);
    query.find({
      success: function(s){
        if(s.length > 0){
          $self.setUserInfo(s[0], $self.user.major, $self);
          $self.alert.create({
            title: 'college saved existed',
            buttons: ['OK']
          }).present();
        }else {
          school = new school();
          school.set('name', $self.user.school.name);
          school.set('address', $self.user.school.formatted_address);
          school.set('location', $self.user.school.geometry.location);
          school.set('place_id', $self.user.school.place_id);
          school.save(null, {
            success: function(school){
              $self.setUserInfo(school, $self.user.major, $self);
              $self.alert.create({
                title: 'college saved',
                buttons: ['OK']
              }).present();
            },
            error: function(error){
              $self.alert.create({
                title: 'college save failed',
                buttons: ['OK']
              }).present();
            }
          });
        }
      },
      error: function(e){
        $self.alert.create({
          title: 'college not found',
          buttons: ['OK']
        }).present();
      }
    });
  }

  setUserInfo(school, major, $self){// sets the user information to be saved
    console.log('saving college in user...');
    let user = this.Parse.User.current();
    user.set('school', school);
    user.set('major', major);
    user.save(null, {
      success: function(user){
        $self.navCtrl.push(MainPage);
      },
      error: function(error){
      }
    }) 

    //console.log(JSON.stringify(user));
  }

  signupForm(){
    if(this.user.schoolName == null || this.user.major == null){
      this.alert.create({
        title: 'Some fields are empty',
        buttons: ["Ok"]
      }).present();
    }else {
      this.setCollegeInfo(this);
    }
  }

}
