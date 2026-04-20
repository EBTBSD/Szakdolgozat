import { Component, OnInit } from '@angular/core';
import { CommonModule, Location } from '@angular/common';
import { RouterLink, ActivatedRoute } from '@angular/router';
import { HttpClient } from '@angular/common/http';
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
  isEditing: boolean = false;
  questions: any[] = [];
  isAddingQuestion: boolean = false;
  newQuestion: any = {
    question_text: '',
    question_type: 'multiple_choice',
    question_points: 1
  };
  activeQuestionIdForAnswer: number | null = null;
  newAnswer: any = { answer_text: '', is_correct: false };
  submissions: any[] = [];
  notificationMessage: string = '';
  isNotificationSuccess: boolean = true;

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

  showNotification(message: string, isSuccess: boolean = true) {
    this.notificationMessage = message;
    this.isNotificationSuccess = isSuccess;
    setTimeout(() => { this.notificationMessage = ''; }, 3000);
  }

  loadAssignmentData() {

    this.http.get(`http://100.96.56.30:8000/api/assignments/${this.assignmentId}`)
      .subscribe({
        next: (res: any) => {
          this.assignment = res.assignment;
          this.loadQuestions();
          this.loadSubmissions();
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
      this.http.delete(`http://100.96.56.30:8000/api/assignments/${this.assignmentId}`)
        .subscribe({
          next: (res: any) => {
            this.showNotification('Feladat törölve!');
            this.goBack();
          },
          error: (err) => {
            console.error('Hiba a törlésnél!', err);
            this.showNotification('Nem sikerült törölni a feladatot.');
          }
        });
    }
  }

  toggleEdit() {
    this.isEditing = !this.isEditing;
  }

  saveChanges() {
    this.http.put(`http://100.96.56.30:8000/api/assignments/${this.assignmentId}`, this.assignment)
      .subscribe({
        next: (res: any) => {
          this.showNotification('Sikeres módosítás!');
          this.assignment = res.assignment;
          this.isEditing = false;
        },
        error: (err) => {
          console.error('Hiba a mentésnél!', err);
          this.showNotification('Nem sikerült a mentés!');
        }
      });
  }

  loadQuestions() {

    this.http.get(`http://100.96.56.30:8000/api/assignments/${this.assignmentId}/questions`)
      .subscribe({
        next: (res: any) => { this.questions = res.questions; },
        error: (err) => { console.error('Hiba a kérdések betöltésekor!', err); }
      });
  }

  toggleAddQuestion() {
    this.isAddingQuestion = !this.isAddingQuestion;
  }

  saveNewQuestion() {

    this.http.post(`http://100.96.56.30:8000/api/assignments/${this.assignmentId}/questions`, this.newQuestion)
      .subscribe({
        next: (res: any) => {
          this.showNotification('Kérdés sikeresen hozzáadva!');
          this.questions.push(res.question);
          this.isAddingQuestion = false;
          this.newQuestion = { question_text: '', question_type: 'multiple_choice', question_points: 1 };
        },
        error: (err) => {
          console.error('Hiba a kérdés mentésekor!', err);
          this.showNotification('Nem sikerült hozzáadni a kérdést.');
        }
      });
  }
  toggleAddAnswer(questionId: number) {
    if (this.activeQuestionIdForAnswer === questionId) {
      this.activeQuestionIdForAnswer = null;
    } else {
      this.activeQuestionIdForAnswer = questionId;
      this.newAnswer = { answer_text: '', is_correct: false };
    }
  }

  saveNewAnswer(questionId: number) {

    this.newAnswer.is_correct = this.newAnswer.is_correct ? 1 : 0;

    this.http.post(`http://100.96.56.30:8000/api/questions/${questionId}/answers`, this.newAnswer)
      .subscribe({
        next: (res: any) => {
          const qIndex = this.questions.findIndex(q => q.id === questionId);
          if (qIndex > -1) {
            if (!this.questions[qIndex].answers) this.questions[qIndex].answers = [];
            this.questions[qIndex].answers.push(res.answer);
          }
          this.activeQuestionIdForAnswer = null;
        },
        error: (err) => {
          console.error('Hiba a válasz mentésekor!', err);
        }
      });
  }

  deleteQuestion(questionId: number) {
    if (confirm('Biztosan törlöd ezt a kérdést? A hozzá tartozó válaszok is eltűnnek!')) {

      this.http.delete(`http://100.96.56.30:8000/api/questions/${questionId}`)
        .subscribe({
          next: () => {
            this.questions = this.questions.filter(q => q.id !== questionId);
          },
          error: (err) => console.error('Hiba a kérdés törlésekor!', err)
        });
    }
  }

  deleteAnswer(questionId: number, answerId: number) {
    if (confirm('Biztosan törlöd ezt a válaszlehetőséget?')) {

      this.http.delete(`http://100.96.56.30:8000/api/answers/${answerId}`)
        .subscribe({
          next: () => {
            const qIndex = this.questions.findIndex(q => q.id === questionId);
            if (qIndex > -1) {
              this.questions[qIndex].answers = this.questions[qIndex].answers.filter((a: any) => a.id !== answerId);
            }
          },
          error: (err) => console.error('Hiba a válasz törlésekor!', err)
        });
    }
  }

  loadSubmissions() {

    this.http.get(`http://100.96.56.30:8000/api/assignments/${this.assignmentId}/submissions`)
      .subscribe({
        next: (res: any) => this.submissions = res.submissions,
        error: (err) => console.error(err)
      });
  }
}