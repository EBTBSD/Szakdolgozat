import { ComponentFixture, TestBed } from '@angular/core/testing';

import { AssignmentDetailsComponent } from './assignment-details';

describe('AssignmentDetailsComponent', () => {
  let component: AssignmentDetailsComponent;
  let fixture: ComponentFixture<AssignmentDetailsComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [AssignmentDetailsComponent]
    })
    .compileComponents();

    fixture = TestBed.createComponent(AssignmentDetailsComponent);
    component = fixture.componentInstance;
    await fixture.whenStable();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
