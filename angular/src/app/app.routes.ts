import { Routes } from '@angular/router';
import { AuthComponent } from './auth/auth';
import { DashboardComponent } from './dashboard/dashboard';
import { CourseComponent } from './course/course';
import { AssignmentDetailsComponent } from './assignment-details/assignment-details';

export const routes: Routes = [
  { path: '', redirectTo: '/auth', pathMatch: 'full' },
  { path: 'auth', component: AuthComponent },
  { path: 'dashboard', component: DashboardComponent },
  { path: 'course/:id', component: CourseComponent },
  { path: 'assignment/:id', component: AssignmentDetailsComponent },
];