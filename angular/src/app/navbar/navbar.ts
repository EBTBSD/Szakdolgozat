import { Component } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Router, RouterModule } from '@angular/router';

@Component({
  selector: 'app-navbar',
  standalone: true,
  imports: [RouterModule],
  templateUrl: './navbar.html',
  styleUrls: ['./navbar.css']
})
export class NavbarComponent {
  alertMessage: string = '';
  alertType: 'success' | 'warning' | 'error' = 'success';

  private apiUrl = 'http://100.96.56.30:8000/api';

  constructor(private http: HttpClient, private router: Router) {}

  logout() {
    this.http.post(`${this.apiUrl}/logout`, {}).subscribe({
      next: (res) => {
        console.log('Sikeres kijelentkezés a szerverről');
        this.executeLogout();
      },
      error: (err) => {
        console.error('Hiba történt a szerver oldali kijelentkezésnél', err);
        this.executeLogout();
      }
    });
  }

  goToProfile() {
    this.router.navigate(['/profile']);
  }

  private executeLogout() {
    localStorage.removeItem('auth_token');
    this.router.navigate(['/auth']);
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