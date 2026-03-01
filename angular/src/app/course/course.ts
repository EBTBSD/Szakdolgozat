import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ActivatedRoute, Router } from '@angular/router';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { FormsModule } from '@angular/forms';
import { NavbarComponent } from '../navbar/navbar';
import { RouterLink } from '@angular/router';

@Component({
  selector: 'app-course',
  standalone: true,
  imports: [CommonModule, NavbarComponent, FormsModule, RouterLink],
  templateUrl: './course.html',
  styleUrls: ['./course.css']
})
export class CourseComponent implements OnInit {
  courseId: string | null = null;
  courseDetails: any = null;
  assignments: any[] = [];
  
  isModalOpen: boolean = false;
  newAssignment: any = {
    assignment_name: '',
    assignment_type: 'Dolgozat',
    assignment_max_point: 100,
    assignment_deadline: '',
    assignment_accessible: '1'
  };

  private apiUrl = 'http://100.96.56.30:8000/api';

  constructor(
    private route: ActivatedRoute, 
    private router: Router,
    private http: HttpClient
  ) {}

  ngOnInit() {
    this.courseId = this.route.snapshot.paramMap.get('id');
    
    if (this.courseId) {
      this.loadCourseData(this.courseId);
    }
  }
  loadCourseData(id: string) {
    const token = localStorage.getItem('auth_token');
    const headers = new HttpHeaders({ 'Authorization': `Bearer ${token}` });

    this.http.get<any>(`${this.apiUrl}/courses/${id}`, { headers }).subscribe({
      next: (valasz: any) => {
        this.courseDetails = valasz.course;
        this.assignments = valasz.assignments;
      },
      error: (hiba: any) => {
        console.error('Hiba a kurzus lekérésekor:', hiba);
      }
    });
  }

  goBack() {
    this.router.navigate(['/dashboard']);
  }

  openModal() { this.isModalOpen = true; }
  closeModal() { this.isModalOpen = false; }

  createAssignment() {
    if (!this.courseId) return;

    const token = localStorage.getItem('auth_token');
    const headers = new HttpHeaders({ 'Authorization': `Bearer ${token}` });
    this.http.post<any>(`${this.apiUrl}/courses/${this.courseId}/assignments/new`, this.newAssignment, { headers }).subscribe({
      next: (valasz: any) => {
        console.log('Siker!', valasz.message);
        
        this.closeModal();
        this.loadCourseData(this.courseId!);
        this.newAssignment = { assignment_name: '', assignment_type: '', assignment_max_point: 100 };
      },
      error: (hiba: any) => {
        console.error('Hiba a feladat létrehozásakor', hiba);
      }
    });
  }
}