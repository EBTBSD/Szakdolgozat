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
  registerData = { lastname: '', firstname: '', email: '', username: '', password: '', password_confirmation: '' };  loginMessage: string = '';
  isLoginError: boolean = false;
  registerMessage: string = '';
  isRegisterError: boolean = false;
  backendErrors: any = {};
  isRegisterSuccess: boolean = false;
  generatedUsername: string = '';

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
      error: (err) => {
        this.isLoginError = true;
        this.loginMessage = err.error?.message || 'Hibás felhasználónév vagy jelszó!';
        console.error(err);
      }
    });
  }

  onRegister() {
    this.backendErrors = {};
    this.isRegisterSuccess = false;
    this.http.post('http://100.96.56.30:8000/api/register', this.registerData).subscribe({
      next: (res: any) => {
        this.isRegisterSuccess = true;
        this.generatedUsername = res.username;
        this.registerData = { lastname: '', firstname: '', email: '', username: '', password: '', password_confirmation: '' };
      },
      error: (err) => {
        if (err.status === 422 && err.error && err.error.errors) {
          this.backendErrors = err.error.errors;
        } else {
          this.backendErrors = "Váratlan hiba történt!";
        }
      }
    });
  }
}