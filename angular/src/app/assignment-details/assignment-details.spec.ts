import { ComponentFixture, TestBed } from '@angular/core/testing';

import { AssignmentDetails } from './assignment-details';

describe('AssignmentDetails', () => {
  let component: AssignmentDetails;
  let fixture: ComponentFixture<AssignmentDetails>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [AssignmentDetails]
    })
    .compileComponents();

    fixture = TestBed.createComponent(AssignmentDetails);
    component = fixture.componentInstance;
    await fixture.whenStable();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
