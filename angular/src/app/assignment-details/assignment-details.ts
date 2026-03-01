import { Component, OnInit } from '@angular/core';
import { CommonModule, Location } from '@angular/common';
import { RouterLink, ActivatedRoute } from '@angular/router';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { FormsModule } from '@angular/forms';

@Component({
  selector: 'app-assignment-details',
  standalone: true,
  imports: [CommonModule, RouterLink, FormsModule],
  templateUrl: './assignment-details.html',
  styleUrls: ['./assignment-details.css']
})
export class AssignmentDetailsComponent implements OnInit {
  assignmentId: string | null = null;
  assignment: any = null;

  constructor(
    private route: ActivatedRoute,
    private http: HttpClient,
    private location: Location
  ) {}

  ngOnInit() {
    this.assignmentId = this.route.snapshot.paramMap.get('id');
    
    if (this.assignmentId) {
      this.loadAssignmentData();
    }
  }

  loadAssignmentData() {
    const token = localStorage.getItem('auth_token'); 
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${token}`
    });

    this.http.get(`http://100.96.56.30:8000/api/assignments/${this.assignmentId}`, { headers })
      .subscribe({
        next: (res: any) => {
          this.assignment = res.assignment;
        },
        error: (err) => {
          console.error('Hiba a feladat betöltésekor!', err);
        }
      });
  }

  goBack() {
    this.location.back();
  }

  deleteAssignment() {
    if (confirm('Biztosan törölni szeretnéd ezt a feladatot? Ezt nem lehet visszavonni!')) {
      const token = localStorage.getItem('token'); 
      const headers = new HttpHeaders({ 'Authorization': `Bearer ${token}` });

      this.http.delete(`http://100.96.56.30:8000/api/assignments/${this.assignmentId}`, { headers })
        .subscribe({
          next: (res: any) => {
            alert('Feladat törölve!');
            this.goBack();
          },
          error: (err) => {
            console.error('Hiba a törlésnél!', err);
            alert('Nem sikerült törölni a feladatot.');
          }
        });
    }
  }

  isEditing: boolean = false;
  toggleEdit() {
    this.isEditing = !this.isEditing;
  }

  saveChanges() {
    const token = localStorage.getItem('token');
    const headers = new HttpHeaders({ 'Authorization': `Bearer ${token}` });

    this.http.put(`http://100.96.56.30:8000/api/assignments/${this.assignmentId}`, this.assignment, { headers })
      .subscribe({
        next: (res: any) => {
          alert('Sikeres módosítás!');
          this.assignment = res.assignment;
          this.isEditing = false;
        },
        error: (err) => {
          console.error('Hiba a mentésnél!', err);
          alert('Nem sikerült a mentés!');
        }
      });
  }
}