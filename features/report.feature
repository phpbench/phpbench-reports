Feature: Reports
    As a benchmarker
    When I import results
    I want to see a summary of the results on the web

    Background:
        Given the user "daniel" exists

    Scenario: View suite report
        Given I have submitted the suite "worse_reflection.xml" as "daniel"
        When I go to "/report/suite/worse-uuid"
        Then I should see the results for "test_case_methods_and_properties"

    Scenario: View benchmark report
        Given I have submitted the suite "worse_reflection.xml" as "daniel"
        When I go to "/report/suite/worse-uuid"
        When I click benchmark "\Phpactor\WorseReflection\Tests\Benchmarks\ReflectMethodBench"
        Then I should see the results for "method_return_type"

    Scenario: View variant report
        Given I have submitted the suite "worse_reflection.xml" as "daniel"
        When I go to "/report/suite/worse-uuid"
        When I click variant "method_return_type"
        Then I should see the iterations report

    Scenario: View user report
        Given I have submitted the suite "worse_reflection.xml" as "daniel"
        When I go to "/user/daniel"
        Then the suite with UUID "worse-uuid" should be listed

    Scenario: View all suites (home)
        Given I have submitted the suite "worse_reflection.xml" as "daniel"
        When I go to "/"
        Then all suites should be listed
