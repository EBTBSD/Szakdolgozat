import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { NavbarComponent } from '../navbar/navbar';
import { Router } from '@angular/router';

@Component({
  selector: 'app-dashboard',
  standalone: true,
  imports: [CommonModule, FormsModule, NavbarComponent],
  templateUrl: './dashboard.html',
  styleUrls: ['./dashboard.css']
})
export class DashboardComponent implements OnInit {
  isModalOpen: boolean = false;

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
    course_img_path: '/images/course_images/1.jpg', 
    course_users: '' 
  };
  private apiUrl = 'http://100.96.56.30:8000/api';
  constructor(private http: HttpClient, private router: Router) {}

  ngOnInit() {
    this.loadDashboardData();
  }
  loadDashboardData() {
    const token = localStorage.getItem('auth_token');
    const headers = new HttpHeaders({ 'Authorization': `Bearer ${token}` });

    this.http.get<any>(`${this.apiUrl}/dashboard-data`, { headers }).subscribe({
      next: (valasz: any) => {
        this.stats = valasz.stats;
        this.courses = valasz.courses;
        this.courses = valasz.courses.map((c: any) => {
          if (c.course_img_path && !c.course_img_path.startsWith('http')) {
            c.course_img_path = 'http://100.96.56.30:8000/' + c.course_img_path.replace(/^\//, '');
          }
          return c;
        });
      },
      error: (hiba: any) => {
        console.error('Hiba az adatok lekérésekor:', hiba);
      }
    });
  }
  openModal() { this.isModalOpen = true; }
  closeModal() { this.isModalOpen = false; }
  createCourse() {
    const token = localStorage.getItem('auth_token');
    const headers = new HttpHeaders({ 'Authorization': `Bearer ${token}` });

    this.http.post<any>(`${this.apiUrl}/courses/new`, this.newCourse, { headers }).subscribe({
      next: (valasz: any) => {
        console.log('Siker!', valasz.message);
        this.closeModal();
        this.loadDashboardData();
        this.newCourse = { course_name: '', course_type: '', course_img_path: '/images/course_images/1.jpg', course_users: '' };
      },
      error: (hiba: any) => {
        console.error('Hiba a kurzus létrehozásakor', hiba);
      }
    });
  }
  goToCourse(courseId: number) {
    this.router.navigate(['/course', courseId]);  
  }
}