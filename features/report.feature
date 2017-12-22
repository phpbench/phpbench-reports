Feature: Reports
    As a benchmarker
    When I import results
    I want to see a summary of the results on the web

    Background:
        Given the user "daniel" exists
        And user "daniel" has project "phpbench" "phpbench"

    Scenario: View latest suites
        Given I have submitted the suite "worse_reflection.xml" for project "phpbench/phpbench"
        When I go to "/latest"
        Then all suites should be listed

    Scenario: View latest suites for a namespace
        Given I have submitted the suite "worse_reflection.xml" for project "phpbench/phpbench"
        When I go to "/p/phpbench"
        Then all suites should be listed

    Scenario: View latest suites for a project
        Given I have submitted the suite "worse_reflection.xml" for project "phpbench/phpbench"
        When I go to "/p/phpbench/phpbench"
        Then all suites should be listed

    Scenario: View suite report
        Given I have submitted the suite "worse_reflection.xml" for project "phpbench/phpbench"
        When I go to "/p/phpbench/phpbench/worse-uuid"
        Then I should see the results for "test_case_methods_and_properties"

    Scenario: View suite benchmark report
        Given I have submitted the suite "worse_reflection.xml" for project "phpbench/phpbench"
        And I am on "/p/phpbench/phpbench/worse-uuid"
        When I click benchmark "ReflectMethodBench"
        Then I should be on "/p/phpbench/phpbench/worse-uuid/ReflectMethodBench"
        And I should see the results for "method_return_type"

    Scenario: View suite variant report
        Given I have submitted the suite "worse_reflection.xml" for project "phpbench/phpbench"
        And I am on "/p/phpbench/phpbench/worse-uuid/ReflectMethodBench"
        When I click variant "method_return_type"
        Then I should be on "/p/phpbench/phpbench/worse-uuid/ReflectMethodBench/method_return_type/0"
        And I should see the iterations report

    Scenario: View suite variant report that has errors
        Given I have submitted the suite "errors.xml" for project "phpbench/phpbench"
        And I am on "/p/phpbench/phpbench/1234/ImportBench"
        Then I should see an error row for "benchImport"

    Scenario: View historical benchmark report
        Given I have submitted the suite "hashing1.xml" for project "phpbench/phpbench"
        And I have submitted the suite "hashing2.xml" for project "phpbench/phpbench"
        And I have submitted the suite "hashing3.xml" for project "phpbench/phpbench"
        When I am on "/p/phpbench/phpbench/bench/HashingBench"
        And I should see the results for "benchMd5"
