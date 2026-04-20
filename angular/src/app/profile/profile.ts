import { Component, OnInit } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { CommonModule, Location } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { NavbarComponent } from '../navbar/navbar';

@Component({
  selector: 'app-profile',
  standalone: true,
  imports: [CommonModule, FormsModule, NavbarComponent],
  templateUrl: './profile.html',
  styleUrls: ['./profile.css']
})
export class ProfileComponent implements OnInit {
  alertMessage: string = '';
  alertType: 'success' | 'warning' | 'error' = 'success';

  userData: any = {
    firstname: '',
    lastname: '',
    email: '',
    password: ''
  };

  apiUrl = 'http://100.96.56.30:8000/api/profile';

  constructor(private http: HttpClient, private location: Location,) {}

  ngOnInit() {
    this.loadProfile();
  }

  loadProfile() {
    this.http.get(this.apiUrl).subscribe({
      next: (res: any) => {
        this.userData.firstname = res.firstname;
        this.userData.lastname = res.lastname;
        this.userData.username = res.username;
        this.userData.email = res.email;
      },
      error: (err) => console.error('Hiba a profil betöltésekor:', err)
    });
  }

  updateProfile() {
    this.http.put(`${this.apiUrl}/update`, this.userData).subscribe({
      next: (res: any) => {
        alert('✅ ' + res.message);
        this.showAlert(res.message, 'success');
        this.userData.password = '';
      },
      error: (err) => {
        console.error('Mentési hiba:', err);
        if (err.status === 422 && err.error.errors) {
          const hibak = Object.values(err.error.errors).flat().join('\n');
          this.showAlert('Hiba a kitöltésnél: ' + hibak, 'error');
        } else {
          this.showAlert('Hiba történt a szerverrel való kommunikáció során!', 'error');
        }
      }
    });
  }

  goBack() {
    this.location.back();
  }

  showAlert(message: string, type: 'success' | 'warning' | 'error') {
    this.alertMessage = message;
    this.alertType = type;
    setTimeout(() => {
      this.alertMessage = '';
    }, 4000);
  }

  closeAlert() {
    this.alertMessage = '';
  }
}