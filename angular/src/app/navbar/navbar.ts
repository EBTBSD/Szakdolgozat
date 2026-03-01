import { Component } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Router } from '@angular/router';

@Component({
  selector: 'app-navbar',
  standalone: true,
  templateUrl: './navbar.html',
  styleUrls: ['./navbar.css']
})
export class NavbarComponent {
  private apiUrl = 'http://100.96.56.30:8000/api';

  constructor(private http: HttpClient, private router: Router) {}

  logout() {
    const token = localStorage.getItem('auth_token');
    const headers = new HttpHeaders({ 'Authorization': `Bearer ${token}` });

    this.http.post(`${this.apiUrl}/logout`, {}, { headers }).subscribe({
      next: (valasz) => {
        console.log('Sikeres kijelentkezés a szerverről');
        this.vegrehajtKijelentkezes();
      },
      error: (hiba) => {
        console.error('Hiba történt a szerver oldali kijelentkezésnél', hiba);
        this.vegrehajtKijelentkezes();
      }
    });
  }

  private vegrehajtKijelentkezes() {
    localStorage.removeItem('auth_token');
    this.router.navigate(['/auth']);
  }
}