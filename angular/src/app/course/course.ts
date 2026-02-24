import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ActivatedRoute, Router } from '@angular/router';
import { NavbarComponent } from '../navbar/navbar';

@Component({
  selector: 'app-course',
  standalone: true,
  imports: [CommonModule, NavbarComponent],
  templateUrl: './course.html',
  styleUrls: ['./course.css']
})
export class CourseComponent implements OnInit {
  courseId: string | null = null;
  
  assignments = [
    { id: 1, assignment_name: 'Első Világháború Esszé', assignment_type: 'Beadandó', assignment_max_point: 50, assignment_succed_point: 45, assignment_grade: 5, assignment_finnished: 2 },
    { id: 2, assignment_name: 'Témazáró', assignment_type: 'Teszt', assignment_max_point: 100, assignment_succed_point: 0, assignment_grade: 0, assignment_finnished: 1 }
  ];

  constructor(private route: ActivatedRoute, private router: Router) {}

  ngOnInit() {
    this.courseId = this.route.snapshot.paramMap.get('id');
    console.log('Megnyitott kurzus ID:', this.courseId);
  }

  goBack() {
    this.router.navigate(['/dashboard']);
  }
}