Feature: List projects
    As a benchmarker
    In order to manage my projects
    I need to be able to see a list of my projects

    Background:
        Given the user "test" exists
        And I am logged in as user "test"
        And user "test" has project "dantleech" "phpbench-reports"

    Scenario: View projects in profile
        When I am on "/profile"
        Then I should see the project "dantleech/phpbench-reports"
