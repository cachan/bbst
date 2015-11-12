Feature: http hotlinking
  In order to prevent image hotlinking
  As an outside web user
  I need to be shown a Go Away! page

  Scenario: Prevent image hotlinking
    Given I am in a web browser
    Given I have an "external" referer
    When I make an HTTP GET request to "http://localhost/referer/hotlink.jpg"
    Then I should be redirected to "http://localhost/referer/goaway.html"
    Then I should see the text "Go Away!"

  Scenario: Allow internal image viewing
    Given I am in a web browser
    Given I have an "internal" referer
    When I make an HTTP GET request to "http://localhost/referer/hotlink.jpg"
    Then I should receive response code "200"
    And I should receive a file with MIME type "image/jpeg"