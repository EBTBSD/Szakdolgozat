import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { HttpClient} from '@angular/common/http';
import { NavbarComponent } from '../navbar/navbar';
import { Router } from '@angular/router';
import { RouterLink } from '@angular/router';

@Component({
  selector: 'app-dashboard',
  standalone: true,
  imports: [CommonModule, FormsModule, NavbarComponent, RouterLink],
  templateUrl: './dashboard.html',
  styleUrls: ['./dashboard.css']
})
export class DashboardComponent implements OnInit {
  isModalOpen: boolean = false;
  alertMessage: string = '';
  alertType: 'success' | 'warning' | 'error' = 'success';

  stats = {
    average: 0,
    ass_perc: 0,
    ass_perc_suc: 0,
    ass_perc_fai: 0,
    ass_perc_out: 0,
    ass_perc_need: 0
  };

  courses: any[] = [];
  newCourse = { 
    course_name: '', 
    course_type: '', 
    course_img_path: '/images/course_images/default.jpg', 
    course_users: '' 
  };
  private apiUrl = 'http://100.96.56.30:8000/api';
  recentAssignments: any[] = [];
  constructor(private http: HttpClient, private router: Router) {}

  ngOnInit() {
    this.loadDashboardData();
  }
  loadDashboardData() {
    this.http.get<any>(`${this.apiUrl}/dashboard-data`).subscribe({
      next: (res: any) => {
        this.stats = res.stats;
        this.recentAssignments = res.assignments;
        this.courses = res.courses;
        this.courses = res.courses.map((c: any) => {
          if (c.course_img_path && !c.course_img_path.startsWith('http')) {
            c.course_img_path = 'http://100.96.56.30:8000/' + c.course_img_path.replace(/^\//, '');
          }
          return c;
        });
      },
      error: (err: any) => {
        console.error('Hiba az adatok lekérésekor:', err);
      }
    });
  }
  openModal() { this.isModalOpen = true; }
  closeModal() { this.isModalOpen = false; }
  createCourse() {
    this.http.post<any>(`${this.apiUrl}/courses/new`, this.newCourse).subscribe({
      next: (res: any) => {
        this.closeModal();
        this.loadDashboardData();
        this.newCourse = { course_name: '', course_type: '', course_img_path: '/images/course_images/1.jpg', course_users: '' };
        this.showAlert('A kurzus sikeresen létrejött!', 'success');
      },
      error: (err: any) => {
        console.error('Hiba a kurzus létrehozásakor', err);
        this.showAlert('Hiba a kurzus létrehozásakor!', 'error');
      }
    });
  }
  goToCourse(courseId: number) {
    this.router.navigate(['/course', courseId]);  
  }
  inviteCode: string = '';

  joinCourse() {
    if (!this.inviteCode.trim()) {
      this.showAlert('Kérlek, add meg a meghívó kódot!', 'warning');
      return;
    }
    this.http.post('http://100.96.56.30:8000/api/join-course', { invite_code: this.inviteCode })
      .subscribe({
        next: (res: any) => {
          this.showAlert(res.message, 'success');
          this.inviteCode = '';
          this.loadDashboardData();
        },
        error: (err) => {
          console.error(err);
          this.showAlert(err.error?.message || 'Hiba történt a csatlakozáskor!', 'error');        }
      });
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