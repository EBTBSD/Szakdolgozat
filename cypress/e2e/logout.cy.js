describe('Kijelentkezés Teszt', () => {
  it('Sikeresen kijelentkezteti a felhasználót', () => {
    cy.visit('http://100.96.56.30:4200/auth');
    cy.get('input[name="username"]').first().type('IXQH');
    cy.get('input[name="password"]').first().type('AlmaGeza0123');
    cy.get('button[type="submit"]').first().click();
    cy.wait(3000); 
    cy.contains('Kijelentkezés').click();
    cy.url().should('include', '/auth');
  });
});