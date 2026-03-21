import { Routes } from '@angular/router';
import { AuthComponent } from './auth/auth';
import { DashboardComponent } from './dashboard/dashboard';
import { CourseComponent } from './course/course';
import { AssignmentDetailsComponent } from './assignment-details/assignment-details';
import { StudentTestComponent } from './student-test/student-test';

export const routes: Routes = [
  { path: '', redirectTo: '/auth', pathMatch: 'full' },
  { path: 'auth', component: AuthComponent },
  { path: 'dashboard', component: DashboardComponent },
  { path: 'course/:id', component: CourseComponent },
  { path: 'assignment/:id', component: AssignmentDetailsComponent },
  { path: 'student-test/:id', component: StudentTestComponent },
  { path: 'assignment-details/:id', component: AssignmentDetailsComponent },
  { path: 'student-test/:id', component: StudentTestComponent },
];