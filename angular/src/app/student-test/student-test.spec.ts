import { ComponentFixture, TestBed } from '@angular/core/testing';

import { StudentTestComponent } from './student-test';

describe('StudentTestComponent', () => {
  let component: StudentTestComponent;
  let fixture: ComponentFixture<StudentTestComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [StudentTestComponent]
    })
    .compileComponents();

    fixture = TestBed.createComponent(StudentTestComponent);
    component = fixture.componentInstance;
    await fixture.whenStable();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
