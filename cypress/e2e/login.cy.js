describe('E-Learning Bejelentkezés Teszt', () => {
  it('Sikeresen bejelentkezik egy létező fiókkal', () => {
    cy.visit('http://100.96.56.30:4200/auth');
    cy.get('input[name="username"]').first().type('IXQH');
    cy.get('input[name="password"]').first().type('AlmaGeza0123');
	  cy.get('button[type="submit"]').first().click();
    cy.wait(5000); 
    cy.url().should('include', '/dashboard');
  });
});