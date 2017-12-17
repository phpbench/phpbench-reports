Feature: Show users API key
    As a benchmarker
    In order to submit my benchmarks from PHPBench
    I need an API key

    Background:
        Given the user "test" exists
        And I am logged in as user "test"

    Scenario: View API key
        Given I am on the profile page
        Then I should see my API key
