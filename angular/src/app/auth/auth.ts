import { Component } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { CommonModule } from '@angular/common';
import { HttpClient } from '@angular/common/http';
import { Router } from '@angular/router';

@Component({
  selector: 'app-auth',
  standalone: true,
  imports: [FormsModule, CommonModule],
  templateUrl: './auth.html',
  styleUrls: ['./auth.css']
})
export class AuthComponent {
  loginData = { username: '', password: '' };
  registerData = { lastname: '', firstname: '', email: '', username: '', password: '', password_again: '' };
  loginMessage: string = '';
  isLoginError: boolean = false;
  registerMessage: string = '';
  isRegisterError: boolean = false;
  private apiUrl = 'http://100.96.56.30:8000/api';

  constructor(private http: HttpClient, private router: Router) {}

  onLogin() {
    this.loginMessage = 'Bejelentkezés folyamatban...';
    this.isLoginError = false;

    this.http.post<any>(`${this.apiUrl}/login`, this.loginData).subscribe({
      next: (valasz) => {
        this.loginMessage = 'Sikeres bejelentkezés!';
        this.isLoginError = false;
        
        localStorage.setItem('auth_token', valasz.token);
        
        this.router.navigate(['/dashboard']); 
      },
      error: (hiba) => {
        this.isLoginError = true;
        this.loginMessage = hiba.error?.message || 'Hibás felhasználónév vagy jelszó!';
        console.error(hiba);
      }
    });
  }

  onRegister() {
    this.http.post('http://100.96.56.30:8000/api/register', this.registerData).subscribe({
      next: (res: any) => {
        alert(`🎉 Sikeres regisztráció!\n\nA Te felhasználó neved: ${res.username}\n\nKérlek, írd fel magadnak, mert ezzel tudsz majd bejelentkezni!`);
        this.router.navigate(['/login']);
      },
      error: (err) => {
        console.error(err);
        alert('Hiba történt a regisztráció során!');
      }
    });
  }
}