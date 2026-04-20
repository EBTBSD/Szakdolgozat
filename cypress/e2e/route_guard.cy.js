describe('Biztonsági Útvonal Teszt', () => {
    it('Nem engedi megnyitni a dashboardot bejelentkezés nélkül', () => {
      cy.visit('http://100.96.56.30:4200/dashboard');
      cy.wait(1000);
      cy.url().should('include', '/auth');
    });
  });