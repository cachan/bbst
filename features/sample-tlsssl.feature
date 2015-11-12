Feature: HTTPS check
  In order to secure data transmission
  As a web user
  I need to communicate over HTTPS

  Scenario: Maximum compatibility HTTPS
    Given I am verifying HTTPS connections
    When I make an HTTPS connection to "columbia.edu"
    Then I should be able to connect with "old" level compatibility

  Scenario: Trusted connection
    Given I am verifying HTTPS connections
    When I make an HTTPS connection to "localhost"
    Then The connection should be trusted

  Scenario: Perfect forward secrecy
    Given I am verifying HTTPS connections
    When I make an HTTPS connection to "columbia.edu"
    Then My connection is protected by perfect forward secrecy