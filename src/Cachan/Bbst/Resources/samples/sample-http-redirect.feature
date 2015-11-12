Feature: http redirect
  In order to visit the proper site
  As a web user
  I need to be redirected properly

  Scenario: Redirect HTTP multiple times
    Given I am in a web browser
    When I make an HTTP GET request to "http://localhost/redirect/begin"
    Then I should be redirected to "http://localhost/redirect/1 http://localhost/redirect/2 http://localhost/redirect/3 http://localhost/redirect/4 http://localhost/redirect/end.html"

  Scenario: Redirect from HTTP to HTTPS
    Given I am in a web browser
    When I make an HTTP GET request to "http://courseworks.columbia.edu"
    Then I should be redirected to "https://courseworks.columbia.edu/"
    And I should see the text '<meta http-equiv="refresh" content="0;url=/welcome">'

  Scenario: Redirect to welcome page
    Given I am in a web browser
    When I make an HTTP GET request to "https://courseworks.columbia.edu/"
    Then I should be redirected to "https://courseworks.columbia.edu/welcome"