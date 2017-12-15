Feature: Reports
    As a benchmarker
    When I import results
    I want to see a summary of the results on the web

    Scenario: View suite report
        Given I have submitted the suite "worse_reflection.xml"
        When I view the resulting report
        Then I should see the results for "test_case_methods_and_properties"

    Scenario: View benchmark report
        Given I have submitted the suite "worse_reflection.xml"
        And am viewing the resulting report
        When I click benchmark "\Phpactor\WorseReflection\Tests\Benchmarks\ReflectMethodBench"
        Then I should see the results for "method_return_type"

    Scenario: View variant report
        Given I have submitted the suite "worse_reflection.xml"
        And am viewing the resulting report
        When I click variant "0" of subject "method_return_type" of benchmark "\Phpactor\WorseReflection\Tests\Benchmarks\ReflectMethodBench"
        Then I should see the iterations report
