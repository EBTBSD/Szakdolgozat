import { Component, OnInit, ViewChild, ElementRef } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ActivatedRoute, Router } from '@angular/router';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { FormsModule } from '@angular/forms';
import { NavbarComponent } from '../navbar/navbar';
import { RouterLink } from '@angular/router';
import { ThisReceiver } from '../../../node_modules/@angular/compiler/types/compiler';

@Component({
  selector: 'app-course',
  standalone: true,
  imports: [CommonModule, NavbarComponent, FormsModule, RouterLink],
  templateUrl: './course.html',
  styleUrls: ['./course.css']
})

export class CourseComponent implements OnInit {
  @ViewChild('fileInput') fileInput!: ElementRef;
  
  alertMessage: string = '';
  alertType: 'success' | 'warning' | 'error' = 'success';
  courseId: string | null = null;
  courseDetails: any = null;
  modules: any[] = []; 
  isTeacher: boolean = false;
  isModalOpen: boolean = false;
  creationType: 'assignment' | 'material' | 'module' = 'assignment';
  selectedModuleId: number | null = null;
  newAssignment: any = {
    assignment_name: '',
    assignment_type: 'Dolgozat',
    assignment_max_point: 100,
    assignment_deadline: '',
    assignment_accessible: 1
  };
  selectedFile: File | null = null;
  uploadMessage: string = '';
  isUploadError: boolean = false;
  newModuleTitle: string = '';
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
    this.http.get<any>(`${this.apiUrl}/courses/${id}`).subscribe({
      next: (res: any) => {
        this.courseDetails = res.course;
        this.modules = res.modules || [];
        this.isTeacher = !!res.is_teacher; 
      },
      error: (err: any) => {
        this.showAlert('Hiba a kurzus lekérésekor!','error');
      }
    });
  }
  goBack() {
    this.router.navigate(['/dashboard']);
  }
  openModal(moduleId: number, type: 'assignment' | 'material' | 'module') {
    this.isModalOpen = true;
    this.selectedModuleId = moduleId;
    this.creationType = type;
    this.uploadMessage = '';
    this.selectedFile = null;
  }
  closeModal() { 
    this.isModalOpen = false; 
    this.selectedModuleId = null;
  }

  createAssignment() {
    if (!this.selectedModuleId) return;
    this.http.post<any>(`${this.apiUrl}/modules/${this.selectedModuleId}/assignments/new`, this.newAssignment)
      .subscribe({
      next: (res: any) => {
        console.log('Siker!', res.message);
        this.closeModal();
        this.loadCourseData(this.courseId!);
        this.newAssignment = { assignment_name: '', assignment_type: 'Dolgozat', assignment_max_point: 100, assignment_deadline: '', assignment_accessible: 1 };
        this.showAlert('Feladat sikeresen létrehozva!', 'success');
      },
      error: (err: any) => {
        this.showAlert('Hiba történt a feladat létrehozásakor!', 'error');
      }
    });
  }
  openAssignment(assignmentId: number) {
    if (this.isTeacher) {
      this.router.navigate(['/assignment-details', assignmentId]);
    } else {
      this.router.navigate(['/student-test', assignmentId]);
    }
  }

  onFileSelected(event: any) {
    const file: File = event.target.files[0];
    if (file) {
      this.selectedFile = file;
      this.uploadMessage = '';
    }
  }
  uploadMaterial() {
    if (!this.selectedFile || !this.selectedModuleId) return;

    this.uploadMessage = 'Feltöltés folyamatban... ⏳';
    this.isUploadError = false;
    
    const formData = new FormData();
    formData.append('file', this.selectedFile);
    this.http.post<any>(`${this.apiUrl}/modules/${this.selectedModuleId}/materials`, formData)
      .subscribe({
        next: (res) => {
          this.uploadMessage = 'Sikeres feltöltés! 🎉';
          this.isUploadError = false;
          this.selectedFile = null;
          if (this.fileInput) {
            this.fileInput.nativeElement.value = ''; 
          }
          this.closeModal();
          this.loadCourseData(this.courseId!);
          this.showAlert('Fájl sikeresen feltöltve!', 'success');
        },
        error: (err) => {
          this.isUploadError = true;
          this.uploadMessage = err.error?.message || 'Hiba történt a feltöltés során! (Túl nagy fájl?)';
          this.showAlert(this.uploadMessage, 'error');
          
        }
      });
  }
  createModule() {
    if (!this.courseId || !this.newModuleTitle) return;
  
    this.http.post<any>(`${this.apiUrl}/courses/${this.courseId}/modules/new`, { module_title: this.newModuleTitle })
      .subscribe({
        next: (res) => {
          this.closeModal();
          this.loadCourseData(this.courseId!); 
          this.newModuleTitle = '';
          this.showAlert('Új modul sikeresen létrehozva!', 'success');
        },
        error: (err) => {
          this.showAlert('Hiba történt a modul létrehozásakor!', 'error');
        }
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
