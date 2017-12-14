Feature:
    As a benchmarker
    When I import results
    I want to see a summary of the results on the web

    Scenario: View suite results
        Given I have submitted the suite "worse_reflection.xml"
        When I view the resulting report
        Then I should see the results for "test_case_methods_and_properties"
