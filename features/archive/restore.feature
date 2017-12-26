Feature: Restore from archive
    As a site administrator
    I want to be able to restore the ES index from the archives
    So that historical data is not lost

    Background:
        Given the user "daniel" exists
        And user "daniel" has project "daniel" "leech" with API key "1234"

    Scenario: Restore from archive
        Given I posted the suite "worse_reflection.xml" with API key "1234"
        And the elastic search index was destroyed
        When I run the command "archive:restore"
        And I go to "/latest"
        Then the suite with UUID "worse-uuid" should be listed
