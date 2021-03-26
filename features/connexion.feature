Feature: Connexion
  Connexion on account

  Scenario: Add credentials on connexion page and click on connexion button
    Given The connexion page
    And Enter credentials on connexion form
    And Click on connexion button
    Then Go to profil page
