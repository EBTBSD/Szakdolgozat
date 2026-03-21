import { Component, OnInit } from '@angular/core';
import { CommonModule, Location } from '@angular/common';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { ActivatedRoute, Router } from '@angular/router';
import { FormsModule } from '@angular/forms';

@Component({
  selector: 'app-student-test',
  standalone: true,
  imports: [CommonModule, FormsModule],
  templateUrl: './student-test.html',
  styleUrls: ['./student-test.css']
})

export class StudentTestComponent implements OnInit {
  assignmentId: string | null = null;
  assignment: any = null;
  questions: any[] = [];
  selectedAnswers: { [key: number]: number } = {}; 
  submission: any = null;
  isCompleted: boolean = false;
  isExpired: boolean = false;
  
  constructor(
    private route: ActivatedRoute,
    private http: HttpClient,
    private router: Router,
    private location: Location
  ) {}

  ngOnInit() {
    this.assignmentId = this.route.snapshot.paramMap.get('id');
    if (this.assignmentId) {
      this.loadTest();
    }
  }

  goBack() {
    this.location.back();
  }

  loadTest() {
    const token = localStorage.getItem('auth_token');
    const headers = new HttpHeaders({ 'Authorization': `Bearer ${token}` });

    this.http.get(`http://100.96.56.30:8000/api/student/assignments/${this.assignmentId}/test`, { headers })
      .subscribe({
        next: (res: any) => {
          this.assignment = res.assignment;
          this.isCompleted = res.is_completed;
          this.isExpired = res.is_expired;
          
          if (this.isCompleted) {
            this.submission = res.submission;
          } else if (!this.isExpired) {
            this.questions = res.questions;
          }
        },
        error: (err) => {
          console.error(err);
          this.location.back();
        }
      });
  }

  submitTest() {
    const token = localStorage.getItem('auth_token');

    if (!token) {
      alert('Nincs bejelentkezési token! Kérlek, jelentkezz ki, majd be újra!');
      return;
    }
    const headers = new HttpHeaders({ 
      'Authorization': `Bearer ${token}`,
      'Accept': 'application/json'
    });

    const payload = {
      answers: this.selectedAnswers
    };
    this.http.post(`http://100.96.56.30:8000/api/student/assignments/${this.assignmentId}/submit`, payload, { headers: headers })
      .subscribe({
        next: (res: any) => {
          alert(`Sikeres beküldés!\nElért pontszám: ${res.achieved_points}\nÉrdemjegy: ${res.grade}`);
          this.location.back();
        },
        error: (err) => {
          console.error('Hiba a beküldéskor:', err);
          alert(err.error?.message || 'Hiba történt a beküldés során!');
        }
      });
  }
}