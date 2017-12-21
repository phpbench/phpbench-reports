Feature: Reports
    As a benchmarker
    When I import results
    I want to see a summary of the results on the web

    - Common NS prefix to be removed from benchmarks on import

    Background:
        Given the user "daniel" exists
        And user "daniel" has project "phpbench" "phpbench"
        And I have submitted the suite "worse_reflection.xml" for project "phpbench/phpbench"

    Scenario: View latest suites
        When I go to "/latest"
        Then all suites should be listed

    Scenario: View latest suites for a namespace
        When I go to "/p/phpbench"
        Then all suites should be listed

    Scenario: View latest suites for a project
        When I go to "/p/phpbench/phpbench"
        Then all suites should be listed

    Scenario: View suite report
        When I go to "/p/phpbench/phpbench/worse-uuid"
        Then I should see the results for "test_case_methods_and_properties"

    Scenario: View benchmark report
        Given I am on "/p/phpbench/phpbench/worse-uuid"
        When I click benchmark "ReflectMethodBench"
        Then I should be on "/p/phpbench/phpbench/worse-uuid/ReflectMethodBench"
        And I should see the results for "method_return_type"

    Scenario: View variant report
        Given I am on "/p/phpbench/phpbench/worse-uuid/ReflectMethodBench"
        When I click variant "method_return_type"
        Then I should be on "/p/phpbench/phpbench/worse-uuid/ReflectMethodBench/method_return_type/0"
        And I should see the iterations report
