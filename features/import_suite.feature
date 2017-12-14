Feature: Upload suite results
    As a benchmarker
    I want my results to be imported
    In order that I can archive them

    Scenario: Upload suite result
        When I upload the suite "worse_reflection.xml"
        Then I should a confirmation with the URL "http://localhost/report/aggregate/suite/133c84a2d96ba55b7006192c42517b419ee4c4ef"
