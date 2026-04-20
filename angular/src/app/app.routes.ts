import { Routes } from '@angular/router';
import { AuthComponent } from './auth/auth';
import { DashboardComponent } from './dashboard/dashboard';
import { CourseComponent } from './course/course';
import { AssignmentDetailsComponent } from './assignment-details/assignment-details';
import { StudentTestComponent } from './student-test/student-test';
import { ProfileComponent } from './profile/profile';
import { AuthGuard } from './auth-guard'; 

export const routes: Routes = [
  { path: '', redirectTo: '/auth', pathMatch: 'full' },
  { path: 'auth', component: AuthComponent },
  { path: 'dashboard', component: DashboardComponent, canActivate: [AuthGuard] },
  { path: 'course/:id', component: CourseComponent, canActivate: [AuthGuard] },
  { path: 'assignment/:id', component: AssignmentDetailsComponent, canActivate: [AuthGuard] },
  { path: 'student-test/:id', component: StudentTestComponent, canActivate: [AuthGuard] },
  { path: 'assignment-details/:id', component: AssignmentDetailsComponent, canActivate: [AuthGuard] },
  { path: 'profile', component: ProfileComponent, canActivate: [AuthGuard] }
];