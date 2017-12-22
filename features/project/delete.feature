Feature: Delete project
    As a benchmarker
    In order to view my benchmarks online
    I need to be able to add projects to my account

    Background:
        Given the user "daniel" exists
        And I am logged in as user "daniel"
        And user "daniel" has project "dantleech" "phpbench-reports"

    Scenario: Delete project
        Given I am on "/profile"
        And I delete project "phpbench-reports"
        Then I should not see project "phpbench-reports"
